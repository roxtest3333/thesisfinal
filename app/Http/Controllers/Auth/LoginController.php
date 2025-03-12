<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Student;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login', ['title' => 'Login Page']);
    }

    public function showLoginForm(Request $request)
    {
        // Force logout before showing login page
        Auth::guard('web')->logout();
        Auth::guard('student')->logout();

        // Invalidate session and regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $remember = $request->has('remember');

        // Attempt Admin Login
        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $user = Auth::guard('web')->user();
            return redirect()->intended('/admin/dashboard')->with('message', 'Welcome, ' . $user->name . '!');
        }

        // Attempt Student Login
        if (Auth::guard('student')->attempt($credentials, $remember)) {
            $student = Auth::guard('student')->user();

            // **Check if email verification is required**
            if (!is_null($student->email_verified_at)) {
                return redirect()->intended('/student/dashboard')->with('message', 'Welcome, ' . $student->first_name . '!');
            } else {
                Auth::guard('student')->logout();
                return back()->withErrors(['email' => 'Your email is not verified. Please check your inbox.']);
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }

    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        } else {
            Auth::guard('student')->logout();
        }

        // Invalidate session and regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('message', 'Logout Successful');
    }
}
