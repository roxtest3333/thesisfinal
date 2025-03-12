<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
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
        ], [
            'student_id.regex' => 'Student ID must be in the format XX-XXXXX.',
            'first_name.regex' => 'First name must contain only letters (A-Z, a-z).',
            'last_name.regex' => 'Last name must contain only letters (A-Z, a-z).',
            'contact_number.digits' => 'Contact number must be exactly 11 digits.',
            'contact_number.numeric' => 'Contact number must contain only numbers.',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Student::create([
            'student_id' => $request->student_id,
            'first_name' => ucfirst(strtolower($request->first_name)), // Capitalize first letter
            'last_name' => ucfirst(strtolower($request->last_name)), // Capitalize first letter
            'email' => strtolower($request->email),
            'course' => strtoupper($request->course),
            'contact_number' => $request->contact_number,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/login')->with('message', 'Registration Successful! Please log in.');
    }
}
