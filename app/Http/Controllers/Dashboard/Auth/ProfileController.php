<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Dashboard\Auth\PasswordUpdateRequest;
use App\Http\Requests\Web\Dashboard\Auth\ProfileUpdateRequest;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    /**
     * Show user profile
     */
    public function showProfile()
    {
        $user = Auth::user();
        return view('dashboard.auth.profile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(ProfileUpdateRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($user->image) {
                Storage::delete('public/profile-images/' . basename($user->image));
            }

            // Store new image
            $imagePath = $request->file('image')->store('profile-images', 'public');
            $data['image'] = $imagePath;
        }

        // Update user data
        $user->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update user password
     */
    public function updatePassword(PasswordUpdateRequest $request)
    {
        $user = Auth::user();

        $data = $request->validated();

        // Update password
        $user->update([
            'password' => Hash::make($data['password'])
        ]);

        return back()->with('success', 'Password updated successfully!');
    }
}
