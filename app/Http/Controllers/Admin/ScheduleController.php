<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\File;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with(['student', 'file', 'schoolYear', 'semester']);
    
        // Fetch dropdown options
        $files = File::all();
        $schoolYears = SchoolYear::all();
        $semesters = Semester::all();
    
        // Get sorting parameters
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
    
        // Validate sort field
        $allowedSortFields = [
            'created_at', 
            'preferred_date', 
            'status', 
            'student_id', 
            'file_id'
        ];
        $sortField = in_array($sortField, $allowedSortFields) ? $sortField : 'created_at';
    
        // Apply filters
        if ($request->file_id) {
            $query->where('file_id', $request->file_id);
        }
    
        if ($request->status) {
            $query->where('status', $request->status);
        }
    
        if ($request->school_year_id) {
            $query->where('school_year_id', $request->school_year_id);
        }
    
        if ($request->semester_id) {
            $query->where('semester_id', $request->semester_id);
        }
    
        // Exclude canceled schedules
        $query->where('status', '!=', 'canceled');
    
        // Dynamic sorting
        if ($sortField === 'student_id') {
            $query->join('students', 'schedules.student_id', '=', 'students.id')
                ->select('schedules.*')
                ->orderBy('students.first_name', $sortDirection)
                ->orderBy('students.last_name', $sortDirection);
        } elseif ($sortField === 'file_id') {
            $query->join('files', 'schedules.file_id', '=', 'files.id')
                ->select('schedules.*')
                ->orderBy('files.file_name', $sortDirection);
        } else {
            $query->orderBy($sortField, $sortDirection);
        }
    
        // Paginate results
        $schedules = $query->paginate(10);
    
        return view('admin.schedules.index', compact(
            'schedules', 
            'files', 
            'schoolYears', 
            'semesters', 
            'sortField', 
            'sortDirection'
        ));
    }
    

public function weeklySchedules(Request $request)
{
    $selectedDay = $request->query('day');

    $query = Schedule::with(['student', 'file'])
        ->whereBetween('preferred_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->where('status', 'approved') // Ensure only approved schedules are retrieved
        ->orderBy('preferred_date', 'asc')
        ->orderBy('preferred_time', 'asc');

    if ($selectedDay) {
        $query->whereDate('preferred_date', Carbon::parse($selectedDay));
    }

    $schedules = $query->paginate(10);

    // Count the number of approved schedules per day for the weekly summary
    $weeklyCounts = Schedule::whereBetween('preferred_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->where('status', 'approved') // Ensure only approved schedules are counted
        ->selectRaw('preferred_date, COUNT(*) as count')
        ->groupBy('preferred_date')
        ->pluck('count', 'preferred_date');

    return view('admin.schedules.weekly', compact('schedules', 'selectedDay', 'weeklyCounts'));
}


    public function getSchedulesByDateRange(Request $request)
    {
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        
        $schedules = Schedule::with(['student', 'file'])
            ->whereBetween('preferred_date', [$startDate, $endDate])
            ->where('status', 'approved')
            ->get()
            ->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'title' => optional($schedule->file)->file_name ?? 'No File',
                    'studentName' => optional($schedule->student)->name ?? 'Unknown Student',
                    'date' => $schedule->preferred_date,
                    'timeSlot' => $schedule->preferred_time,
                    'status' => $schedule->status,
                    'remarks' => $schedule->remarks
                ];
            });

        return response()->json($schedules);
    }

    public function approve(Schedule $schedule)
    {
        $schedule->update([
            'status' => 'approved',
            'approved_at' => now()
        ]);
        return back()->with('success', 'Schedule approved successfully.');
    }

    public function reject(Request $request, Schedule $schedule)
{
    $request->validate([
        'rejection_reason' => 'required|string|max:255',
    ]);

    $schedule->update([
        'status' => 'rejected',
        'remarks' => $request->rejection_reason,
    ]);

    return redirect()->back()->with('success', 'Schedule rejected successfully.');
}


    public function pendingSchedules()
    {
        $schedules = Schedule::with(['student', 'file'])
            ->where('status', 'pending')
            ->orderBy('preferred_date', 'asc')
            ->paginate(10);

        return view('admin.schedules.pending', compact('schedules'));
    }

    public function todaySchedules()
{
    $today = Carbon::today(); // Get today's date

    $schedules = Schedule::with(['student', 'file'])
        ->whereDate('preferred_date', $today) 
        ->where('status', 'approved') 
        ->orderBy('preferred_time', 'asc') 
        ->paginate(10);

    return view('admin.schedules.today', compact('schedules'));
}


   

    public function studentSchedules($studentId)
    {
        $schedules = Schedule::with('file')
            ->where('student_id', $studentId)
            ->orderBy('preferred_date', 'desc')
            ->paginate(10);

        // Count requests per file type for this student
        $requestCounts = Schedule::where('student_id', $studentId)
            ->selectRaw('file_id, COUNT(*) as total_requests')
            ->groupBy('file_id')
            ->with('file')
            ->get();

        return view('admin.schedules.student', compact('schedules', 'requestCounts'));
    }
}
