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

    // Re-fetch student to check if verified
    $student = Student::where('email', $request->email)->first();
    
    if ($student && !$student->hasVerifiedEmail()) {
        return back()->withErrors(['email' => 'Please verify your email before logging in.'])->withInput();
    }

    // Try admin login
    if (Auth::guard('web')->attempt($credentials, $remember)) {
        return redirect()->intended('/admin/dashboard')->with('message', 'Welcome, Admin!');
    }

    // Try student login
    if (Auth::guard('student')->attempt($credentials, $remember)) {
        return redirect()->intended('/student/dashboard')->with('message', 'Welcome, ' . Auth::guard('student')->user()->first_name . '!');
    }

    return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
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
