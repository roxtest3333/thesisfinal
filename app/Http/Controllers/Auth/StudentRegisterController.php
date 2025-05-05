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
            'middle_name' => ['required', 'string', 'min:2','max:255', 'regex:/^[A-Za-z\s]+$/'],
            'last_name' => ['required', 'string', 'min:2',  'max:255', 'regex:/^[A-Za-z]+$/'],
            'sex' => ['required', 'in:Male,Female'],
            'birthday' => ['required', 'date', 'before:-15 years'], // At least 15 years old
            'birthplace' => ['required', 'string', 'min:5','max:255'],
            'email' => ['required', 'email', 'unique:students,email'],
            'course' => ['required', 'string', 'in:BAT,BSA,BEED,BSED,BSCS,BSHM'],
            'contact_number' => ['required', 'digits:11', 'numeric', 'regex:/^09\d{9}$/'],
            'home_address' => ['required', 'min:10','string', 'max:500'],
            'password' => ['required', 'min:8', 'confirmed'],
            'terms' => ['required', 'accepted'],
        ], [
            'student_id.regex' => 'The student ID must be in the format XX-XXXXXX (2 digits, hyphen, then 1-6 digits).',
            'first_name.regex' => 'First name may only contain letters and spaces.',
            'middle_name.min' => 'Middle name must be at least 2 characters.',
            'last_name.regex' => 'Last name may only contain letters.',
            'sex.in' => 'Please select either Male or Female.',
            'birthday.before' => 'You must be at least 15 years old to register.',
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
            'middle_name' => $request->middle_name ? ucfirst(strtolower($request->middle_name)) : null,
            'last_name' => ucfirst(strtolower($request->last_name)),
            'sex' => $request->sex,
            'birthday' => $request->birthday,
            'birthplace' => ucwords(strtolower($request->birthplace)),
            'email' => strtolower($request->email),
            'course' => strtoupper($request->course),
            'contact_number' => $request->contact_number,
            'home_address' => $request->home_address,
            'password' => Hash::make($request->password),
            'email_verified_at' => null,
        ]);

        // Send verification email
        $student->sendEmailVerificationNotification();

        return redirect('/login')->with('message', 'Registration successful! Please check your email to verify your account.');
    }
}