<?php 

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StudentProfileController extends Controller
{
    public function show()
    {
        try {
            $student = Auth::guard('student')->user();
            
            if (!$student) {
                dd([
                    'error' => 'No student found',
                    'student_guard_check' => Auth::guard('student')->check(),
                    'web_guard_check' => Auth::guard('web')->check(),
                    'session' => session()->all()
                ]);
            }
    
            return view('student.profile.show', ['student' => $student]);
    
        } catch (\Exception $e) {
            dd([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function edit()
    {
        $student = Auth::guard('student')->user();
        return view('student.profile.edit', compact('student'));
    }

    public function update(Request $request)
{
    $student = Student::find(Auth::guard('student')->id());

    $validationRules = [
        'first_name' => 'required|string|min:2|max:255|regex:/^[A-Za-z\s]+$/',
        'middle_name' => 'nullable|string|min:2|max:255|regex:/^[A-Za-z\s]+$/',
        'last_name' => 'required|string|min:2|max:255|regex:/^[A-Za-z]+$/',
        'sex' => 'required|in:Male,Female',
        'birthday' => 'required|date|before:-13 years',
        'birthplace' => 'required|string|min:5|max:255',
        'email' => 'required|email|unique:students,email,' . $student->id,
        'course' => 'required|string|in:BAT,BSA,BEED,BSED,BSCS,BSHM',
        'contact_number' => 'required|digits:11|numeric|regex:/^09\d{9}$/',
        'home_address' => 'required|min:10|string|max:500',
    ];

    if ($request->filled('password')) {
        $validationRules['password'] = 'required|min:8|confirmed';
    }

    $validated = $request->validate($validationRules, [
        'first_name.regex' => 'First name may only contain letters and spaces.',
        'middle_name.regex' => 'Middle name may only contain letters and spaces.',
        'last_name.regex' => 'Last name may only contain letters.',
        'contact_number.regex' => 'Contact number must start with 09 and be 11 digits total.',
        'birthday.before' => 'You must be at least 13 years old.',
    ]);

    // Update the student's attributes
    $student->fill($validated);

    if ($request->filled('password')) {
        $student->password = Hash::make($validated['password']);
    }

    $student->save();

    return redirect()->route('student.profile.show')
        ->with('message', 'Profile updated successfully');
}
}
