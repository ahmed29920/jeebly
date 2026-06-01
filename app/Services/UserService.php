<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    protected $userRepository;
    protected OtpIqService $otpIqService;

    public function __construct(UserRepository $userRepository, OtpIqService $otpIqService)
    {
        $this->userRepository = $userRepository;
        $this->otpIqService = $otpIqService;
    }

    public function createUser(array $data)
    {
        $invited_by = null;
        if (isset($data['invitation_code'])) {
            $inviter = $this->userRepository->findByVerificationCode($data['invitation_code']);
            if ($inviter) {
                $invited_by = $inviter->id;

                $inviter->increment('invited_count');

                if ($inviter->invited_count == 10) {
                    $inviter->points += setting('invitation_discount_points');
                    $inviter->has_invitation_discount = true;
                    $inviter->invited_count = 0;
                    $inviter->save();
                }
            } else {
                abort(400, __('messages.invalid_invitation_code'));
            }
        }

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'],
            'gender' => $data['gender'] ?? null,
            'password' => Hash::make($data['password']),
            'is_active' => 1,
            'is_verified' => 0,
            'verification_code' => rand(100000, 999999),
            'invited_by' => $invited_by,
            'role' => $data['role'] ?? 'user',
            'invitation_code' => random_int(100000, 999999),
            'branch_id' => $data['branch_id'] ?? null,
        ];

        if (isset($data['image'])) {
            $userData['image'] = $data['image']->store('uploads/users', 'public');
        }
        $user =  $this->userRepository->create($userData);

        if (isset($data['role']) && $data['role'] === 'user') {
            $user->assignRole('user');
        } elseif (isset($data['role']) && $data['role'] === 'admin') {
            if (!empty($data['assigned_role']) && $data['assigned_role'] !== 'user') {
                $user->assignRole($data['assigned_role']);
            } else {
                $user->assignRole('admin');
            }
        } elseif (isset($data['role']) && $data['role'] === 'employee') {
            $user->assignRole('employee');
        } else {
            $user->assignRole('user');
        }

        // send verification code to user using otpiq
        $this->otpIqService->sendOtp(
            phoneNumber: (string) $user->phone,
            verificationCode: (string) $user->verification_code,
            provider: 'whatsapp',
            smsType: 'verification'
        );

        return $user;
    }

    public function updateUser(User $user, array $data)
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'gender' => $data['gender'],
        ];

        if (isset($data['image'])) {
            $userData['image'] = $data['image']->store('uploads/users', 'public');
        }

        return $this->userRepository->update($user, $userData);
    }

    public function deleteUser(User $user)
    {
        return $this->userRepository->delete($user);
    }

    public function findUserByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }
    public function findUserByPhone(string $phone): ?User
    {
        return $this->userRepository->findByPhone($phone);
    }
    public function verifyUser(array $data)
    {
        $user = $this->findUserByPhone($data['phone']);
        if (!$user) {
            return response()->json([
                'message' => __('messages.user_not_found')
            ], 404);
        }
        if ($user && $user->verification_code != $data['code']) {
            return response()->json([
                'message' => __('messages.invalid_code')
            ], 422);
        }
        $userData['is_active'] = 1;
        $userData['is_verified'] = 1;
        $userData['email_verified_at'] = Carbon::now();
        $userData['verification_code'] = null;
        $userData['verification_code_expire'] = null;
        $this->userRepository->update($user, $userData);
        $token = $user->createToken('auth_token')->plainTextToken;
        $this->syncStoredPhoneFormat($user->fresh());

        return response()->json([
            'message' => __('messages.phone_verified_successfully'),
            'data' => $user,
            'token' => $token
        ], 200);
    }
    public function login(array $data)
    {
        $user = $this->findUserByPhone($data['phone']);

        if (!$user) {
            return response()->json([
                'message' => __('messages.user_not_found')
            ], 404);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => __('messages.you_account_is_inactivated')
            ], 422);
        }

        if (!Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => __('messages.invalid_password')
            ], 422);
        }

        $this->syncStoredPhoneFormat($user);
        $user->refresh();

        $token = $user->createToken('auth_token')->plainTextToken;

        $this->syncStoredPhoneFormat($user);

        return response()->json([
            'message' => __('messages.user_loged_successfully'),
            'data' => [
                'token' => $token,
                'user'  => $user,
            ]
        ], 200);
    }
    public function deleteAccount()
    {
        $user = Auth::user();
        if ($user) {
            $this->userRepository->delete($user);
            return response()->json([
                'message' => __('messages.account_deleted_successfully')
            ]);
        } else {
            return response()->json([
                'message' => __('messages.no_authenticated_user_found')
            ], 401);
        }
    }
    public function changePassword(array $data)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'message' => __('messages.no_authenticated_user_found')
            ], 401);
        }

        if (!Hash::check($data['current_password'], $user->password)) {
            return response()->json([
                'message' => __('messages.current_password_does_not_match')
            ], 422);
        }

        $data['password'] = Hash::make($data['password']);
        $this->userRepository->update($user, $data);

        return response()->json([
            'message' => __('messages.password_changed_successfully')
        ]);
    }
    public function resetPasswordSendOTP(array $data)
    {
        $user = $this->findUserByPhone($data['phone']);
        if (!$user) {
            return response()->json([
                'message' => __('messages.user_not_found')
            ], 404);
        }

        // $code = Str::random(6);
        $code = rand(100000, 999999);
        $this->userRepository->update($user, ['verification_code' => $code, 'verification_code_expire' => Carbon::now()->addMinutes(10)]);

        // Send code to user
        $this->otpIqService->sendOtp(
            phoneNumber: (string) $user->phone,
            verificationCode: (string) $code,
            provider: 'whatsapp',
            smsType: 'verification'
        );

        return response()->json([
            'message' => __('messages.password_reset_opt_code_sent'),
        ]);
    }
    public function resetPasswordVerifyOTP(array $data)
    {
        $user = $this->findUserByPhone($data['phone']);
        if (!$user) {
            return response()->json([
                'message' => __('messages.user_not_found')
            ], 404);
        }
        if (!$user || $user->verification_code !== $data['code']) {
            return response()->json(['message' => __('messages.verification_code_invalid')], 422);
        }

        if ($user->verification_code_expire < now()) {
            return response()->json(['message' => __('messages.verification_code_expired')], 422);
        }

        $resetToken = Str::uuid();

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'phone' => $user->phone,
            'token' => $resetToken,
            'created_at' => now(),
        ]);

        // Here send code to user
        return response()->json([
            'message' => __('messages.password_reset_opt_code_is_verified'),
            'reset_token' => $resetToken,
        ]);
    }
    public function setNewPassword(array $data)
    {

        $record = DB::table('password_reset_tokens')->where('token', $data['reset_token'])->first();

        if (!$record || Carbon::parse($record->created_at)->addMinutes(15) < now()) {
            return response()->json(['message' => __('messages.invalid_or_expired_token')], 422);
        }
            
        
       $user = null;

if (!empty($record->email)) {
    $user = $this->findUserByEmail($record->email);
}

if (!$user && !empty($record->phone)) {
    $user = $this->findUserByPhone($record->phone);
}

if (!$user) {
    return response()->json([
        'message' => __('messages.user_not_found')
    ], 404);
}

        $user->update([
            'password' => Hash::make($data['password']),
            'verification_code' => null,
            'verification_code_expire' => null,
        ]);

        // delete token after use
        DB::table('password_reset_tokens')->where('token', $data['reset_token'])->delete();

        return response()->json([
            'message' => __('messages.password_changed_successfully')
        ]);
    }

    public function getUsersByRole(string $role)
    {
        return $this->userRepository->allByRole($role);
    }

    public function paginateUsersByRole(string $role, int $perPage = 15)
    {
        return $this->userRepository->paginateByRole($role, $perPage);
    }

    protected function syncStoredPhoneFormat(User $user): void
    {
        $stored = $user->getRawOriginal('phone');
        $normalized = normalize_phone($stored);

        if ($normalized && $stored !== $normalized) {
            $this->userRepository->update($user, ['phone' => $normalized]);
        }
    }
}
