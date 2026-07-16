<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ChangePasswordRequest;
use App\Http\Requests\Api\Auth\EditProfileRequest;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Http\Requests\Api\Auth\VerifyPhoneRequest;
use App\Http\Requests\Api\Auth\VerifyRequest;
use App\Models\User;
use App\Services\UserService;
use Carbon\Carbon;
use Filament\Auth\Pages\EditProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = $this->userService->createUser($data);
        return response()->json([
            'message' => __('messages.account_created_please_verifiy_your_phone'),
            'data'    => $user
        ]);
    }
    public function verifyPhone(VerifyRequest $request)
    {
        $data = $request->validated();
        return  $this->userService->verifyUser($data);
    }
    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        return $this->userService->login($data);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => __('messages.logged_out_successfully')
        ], 200);
    }
    public function profile()
    {
        $user = Auth::user();
        $user->load(['addresses', 'branch']);

        return response()->json([
            'data' => $user,
        ]);
    }
    public function deleteAccount()
    {
        return $this->userService->deleteAccount();
    }
    public function editProfile(EditProfileRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();
        return $this->userService->updateUser($user, $data);
    }
    public function changePassword(ChangePasswordRequest $request)
    {
        $data = $request->validated();
        return $this->userService->changePassword($data);
    }
    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string'
        ]);
        $user = Auth::user();
        $user->update([
            'fcm_token' => $request->fcm_token
        ]);
        return response()->json([
            'message' => __('messages.fcm_token_updated_successfully')
        ]);
    }
    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications;
        return response()->json([
            'data' => $notifications
        ]);
    }
    public function markAsRead($id)
    {
        $user = Auth::user();
    
        $notification = $user->notifications()->where('id', $id)->first();
    
        if (!$notification) {
            return response()->json([
                'message' => __('messages.notification_not_found')
            ], 404);
        }
    
        $notification->markAsRead();
    
        return response()->json([
            'message' => __('messages.notification_marked_as_read')
        ]);
    }
    public function markAllAsRead()
    {
        $user = Auth::user();
    
        $user->unreadNotifications->markAsRead();
    
        return response()->json([
            'message' => __('messages.all_notifications_marked_as_read')
        ]);
    }
}
