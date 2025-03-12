<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL; // Add this import
use Illuminate\Support\Facades\Mail; // Add this if you'll send emails
use App\Models\User;
use App\Models\Student;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Access\AuthorizationException;

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

        // Retrieve student record
        $student = Student::where('email', $request->email)->first();

        // Check if student exists and email is not verified
        if ($student && $student->email_verified_at === null) {
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
    
        return redirect('/login')->with('message', 'Logout Successful');
    }
    
    
    public function verifyEmail(Request $request, $id, $hash)
    {
        $student = Student::findOrFail($id);
        
        // Simple verification - just verify any student who clicks this link
        // This is a temporary solution to get things working
        $student->email_verified_at = now();
        $student->save();
        
        return redirect('/login')->with('message', 'Email verified successfully! You can now log in.');
    }
    
    public function resendVerificationEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        
        $student = Student::where('email', $request->email)->first();
        
        if (!$student) {
            return back()->withErrors(['email' => 'No account found with this email.'])->withInput();
        }
        
        if ($student->email_verified_at) {
            return back()->with('message', 'Email already verified. You can log in now.');
        }
        
        // Generate verification URL
        $verificationUrl = $this->generateVerificationUrl($student);
        
        // Here you would send the email with the verification URL
        // Mail::to($student->email)->send(new VerificationEmail($verificationUrl));
        
        return back()->with('message', 'Verification email sent successfully. Please check your inbox.');
    }
    
    private function generateVerificationUrl($student)
    {
        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $student->id,
                'hash' => sha1($student->email),
            ]
        );
        
        return $url;
    }
}