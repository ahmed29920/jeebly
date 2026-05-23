<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Http\Requests\Api\Auth\SetNewPasswordRequest;
use App\Http\Requests\Api\Auth\VerifyRequest;
use App\Services\UserService;

class ResetPasswordController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function resetPasswordSendOTP(ResetPasswordRequest $request)
    {
        $data = $request->validated();
        return $this->userService->resetPasswordSendOTP($data);
    }
    public function resetPasswordVerifyOTP(VerifyRequest $request)
    {
        $data = $request->validated();
        return $this->userService->resetPasswordVerifyOTP($data);
    }
    public function setNewPassword(SetNewPasswordRequest $request)
    {
        $data = $request->validated();
        return $this->userService->setNewPassword($data);
    }
}
