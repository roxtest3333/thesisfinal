<?php

namespace App\Http\Controllers\Student;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Schedule;
use App\Models\SchoolYear;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
class ScheduleController extends Controller
{
    /**
     * Show the schedule request form.
     */
    public function create()
{
    $files = File::where('is_available', true)->get(); // Retrieve available files
    $schoolYears = SchoolYear::with('semesters')->orderBy('year', 'desc')->get();
    $semesters = Semester::all(); // Ensure semesters are retrieved

    return view('student.schedules.create', compact('files', 'schoolYears', 'semesters'));
}


    /**
     * Store a new schedule request.
     */
    public function store(Request $request)
{
    $student = Auth::guard('student')->user();
    if (!$student) {
        return redirect()->route('student.dashboard')->with('error', 'You must be logged in as a student to submit a request.');
    }

    $validated = $request->validate([
        'preferred_date' => [
            'required', 
            'date', 
            'after_or_equal:' . now()->addDays(3)->toDateString(), // At least 3 days from today
            'before_or_equal:' . now()->addDays(7)->toDateString(), // Maximum 7 days from today
            function ($attribute, $value, $fail) {
                if (Carbon::parse($value)->isWeekend()) {
                    $fail('Scheduling on weekends is not allowed. Please choose a weekday.');
                }
            }
        ],
        'file_id' => 'required|exists:files,id',
        'preferred_time' => 'required|in:AM,PM',
        'reason' => 'required|string|max:255',
        'manual_school_year' => 'nullable|string|max:255',
        'manual_semester' => 'nullable|string|max:255',
        'copies' => 'required|integer|min:1',
    ]);

    $schoolYear = SchoolYear::orderBy('year', 'desc')->first();
    $semester = Semester::where('school_year_id', $schoolYear->id)->orderBy('id', 'desc')->first();

    if (!$schoolYear || !$semester) {
        return back()->withErrors(['error' => 'No active school year or semester found. Please contact an administrator.']);
    }

    $file = File::find($request->file_id);
    if (in_array(optional($file)->file_name, ['COR', 'COG'])) {
        if (!$request->manual_school_year || !$request->manual_semester) {
            return back()->withErrors(['manual_school_year' => 'School year and semester are required for COR/COG requests.']);
        }
    } else {
        $request->merge(['manual_school_year' => null, 'manual_semester' => null]);
    }

    try {
        Schedule::create([
            'student_id' => $student->id,
            'file_id' => $request->file_id,
            'preferred_date' => $request->preferred_date,
            'preferred_time' => $request->preferred_time,
            'reason' => $request->reason,
            'manual_school_year' => $request->manual_school_year,
            'manual_semester' => $request->manual_semester,
            'copies' => $request->copies,
            'school_year_id' => $schoolYear->id,
            'semester_id' => $semester->id,
            'status' => 'Pending',
        ]);

        return redirect()->route('student.dashboard')->with('success', 'Schedule request submitted successfully.');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'An error occurred while processing your request. Please try again later.']);
    }
}

public function cancelRequest($id)
{
    $student = Auth::guard('student')->user();
    $schedule = Schedule::where('id', $id)
                        ->where('student_id', $student->id)
                        ->where('status', 'Pending')
                        ->first();

    if (!$schedule) {
        return back()->with('error', 'Schedule request not found or already processed.');
    }

    // Delete the schedule request
    $schedule->delete();

    // Redirect back with success message (this ensures dashboard updates)
    return redirect()->route('student.dashboard')->with('success', 'Schedule request canceled successfully.');
}

}

    

