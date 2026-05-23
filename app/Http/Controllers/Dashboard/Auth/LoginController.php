<?php

namespace App\Http\Controllers\Dashboard\Auth;
use App\Http\Requests\Web\Dashboard\Auth\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('dashboard.auth.login');
    }

    /**
     * Handle login request
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            if($user->role == 'admin'){
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }elseif($user->role == 'employee'){
                $request->session()->regenerate();
                return redirect()->route('branch.dashboard');
            }else{
                return redirect()->back()->with('error', 'You are not authorized to access this page.');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Logout method
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
