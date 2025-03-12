<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.student-register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => ['required', 'regex:/^\d{2}-\d{5}$/', 'unique:students,student_id'],
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z]+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z]+$/'],
            'email' => ['required', 'email', 'unique:students,email'],
            'course' => ['required', 'string'],
            'contact_number' => ['required', 'digits:11', 'numeric'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $student = Student::create([
            'student_id' => $request->student_id,
            'first_name' => ucfirst(strtolower($request->first_name)),
            'last_name' => ucfirst(strtolower($request->last_name)),
            'email' => strtolower($request->email),
            'course' => strtoupper($request->course),
            'contact_number' => $request->contact_number,
            'password' => Hash::make($request->password),
        ]);

        // Send verification email
        $student->sendEmailVerificationNotification();

        return redirect()->route('verification.notice')->with('message', 'Registration successful! Please verify your email.');
    }
}
