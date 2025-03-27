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
use Exception;
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
        try {
            $student = Auth::guard('student')->user();
    
            // Get the latest school year and semester
            $latestSchoolYear = SchoolYear::latest('created_at')->first();
            $latestSemester = Semester::latest('created_at')->first();
    
            // Ensure they exist
            if (!$latestSchoolYear || !$latestSemester) {
                return redirect()->back()->with('error', 'No school year or semester has been set by the admin.');
            }
    
            // Calculate valid date range (3-7 working days from today)
            $today = Carbon::today();
            $minDate = $this->addWorkingDays($today->copy(), 3);
            $maxDate = $this->addWorkingDays($today->copy(), 7);
    
            // Validate the request
            $validated = $request->validate([
                'file_id' => 'required|integer',
                'manual_school_year' => 'nullable|string',
                'manual_semester' => 'nullable|string',
                'preferred_date' => [
                    'required',
                    'date',
                    function ($attribute, $value, $fail) use ($minDate, $maxDate) {
                        $selectedDate = Carbon::parse($value);
    
                        if ($selectedDate->lessThan($minDate) || $selectedDate->greaterThan($maxDate)) {
                            $fail("The selected date must be between {$minDate->toDateString()} and {$maxDate->toDateString()}, excluding weekends.");
                        }
    
                        if ($selectedDate->isWeekend()) {
                            $fail("The selected date falls on a weekend. Please choose a working day.");
                        }
                    }
                ],
                'preferred_time' => 'required|string',
                'reason' => 'nullable|string',
                'copies' => 'required|integer',
            ]);
    
            // Assign the latest school year and semester
            $validated['school_year_id'] = $latestSchoolYear->id;
            $validated['semester_id'] = $latestSemester->id;
            $validated['status'] = 'pending';
            $validated['student_id'] = $student->id;
    
            Schedule::create($validated);
    
            return redirect()->route('student.dashboard')->with('message', 'Schedule created successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong! ' . $e->getMessage());
        }
    }
    
    /**
     * Helper function to add working days (excluding weekends).
     */
    private function addWorkingDays($date, $days)
    {
        $count = 0;
        while ($count < $days) {
            $date->addDay();
            if (!$date->isWeekend()) {
                $count++;
            }
        }
        return $date;
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

    

