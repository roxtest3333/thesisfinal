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
            'student_id' => ['required', 'regex:/^\d{2}-\d{1,6}$/', 'unique:students,student_id'],
            'first_name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[A-Za-z\s]+$/'],
            'last_name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[A-Za-z]+$/'],
            'email' => ['required', 'email', 'unique:students,email'],
            'course' => ['required', 'string', 'in:BAT,BSA,BEED,BSED,BSCS,BSHM'],
            'contact_number' => ['required', 'digits:11', 'numeric', 'regex:/^09\d{9}$/'],
            'password' => ['required', 'min:8', 'confirmed'],
            'terms' => ['required', 'accepted'],
        ], [
            'student_id.regex' => 'The student ID must be in the format XX-XXXXXX (2 digits, hyphen, then 1-6 digits).',
            'first_name.regex' => 'First name may only contain letters and spaces.',
            'last_name.regex' => 'Last name may only contain letters.',
            'contact_number.regex' => 'Contact number must start with 09 and be 11 digits total.',
            'terms.required' => 'You must accept the terms and conditions.',
            'course.in' => 'Please select a valid course.',
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

        return redirect('/login')->with('message', 'Registration successful! Please check your email to verify your account.');
    }
}