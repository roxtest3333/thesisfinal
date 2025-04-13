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
        
        //shows only records created in last 30 days
        $query->where('created_at', '>', now()->subDays(30)); 
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
    
        // Handle status filter (can be single value or comma-separated)
        if ($request->status) {
            $statuses = is_array($request->status) 
                ? $request->status 
                : explode(',', $request->status);
            
            $query->whereIn('status', $statuses);
        } else {
            // By default, show active schedules (exclude completed)
            $query->whereIn('status', ['pending', 'approved']);
        }
    
        if ($request->school_year_id) {
            $query->where('school_year_id', $request->school_year_id);
        }
    
        if ($request->semester_id) {
            $query->where('semester_id', $request->semester_id);
        }
    
        // Date range filtering
        if ($request->start_date) {
            $query->whereDate('preferred_date', '>=', $request->start_date);
        }
    
        if ($request->end_date) {
            $query->whereDate('preferred_date', '<=', $request->end_date);
        }
    
        // Enhanced search by student name or ID
        if ($request->search) {
            $searchTerm = '%' . preg_replace('/\s+/', '%', trim($request->search)) . '%';
            $query->whereHas('student', function($q) use ($searchTerm) {
                $q->where('student_id', 'LIKE', $searchTerm)
                  ->orWhere('first_name', 'LIKE', $searchTerm)
                  ->orWhere('last_name', 'LIKE', $searchTerm)
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$searchTerm])
                  ->orWhereRaw("CONCAT(last_name, ' ', first_name) LIKE ?", [$searchTerm])
                  ->orWhereRaw("CONCAT(last_name, ', ', first_name) LIKE ?", [$searchTerm])
                  ->orWhereRaw("LOWER(CONCAT(first_name, ' ', last_name)) LIKE LOWER(?)", [$searchTerm]);
            });
        }
    
        // Exclude canceled schedules
        $query->where('status', '!=', 'canceled');
    
        // Dynamic sorting
        if ($sortField === 'student_id') {
            $query->join('students', 'schedules.student_id', '=', 'students.id')
                ->select('schedules.*')
                ->orderBy('students.last_name', $sortDirection)
                ->orderBy('students.first_name', $sortDirection);
        } elseif ($sortField === 'file_id') {
            $query->join('files', 'schedules.file_id', '=', 'files.id')
                ->select('schedules.*')
                ->orderBy('files.file_name', $sortDirection);
        } else {
            $query->orderBy($sortField, $sortDirection);
        }
    
        // Paginate results with query string
        $schedules = $query->paginate(10);
        $schedules->appends(request()->query());
    
        return view('admin.schedules.index', compact(
            'schedules', 
            'files', 
            'schoolYears', 
            'semesters', 
            'sortField', 
            'sortDirection'
        ));
    }
    
    public function getSemesters(Request $request)
    {
        $schoolYearId = $request->input('school_year_id');
        
        if (!$schoolYearId) {
            return response()->json(['message' => 'School year ID is required'], 400);
        }
        
        $semesters = Semester::where('school_year_id', $schoolYearId)->get();
        
        return response()->json($semesters);
    }

    /* public function pendingSchedules()
    {
        $schedules = Schedule::with(['student', 'file'])
            ->where('status', 'pending')
            ->orderBy('preferred_date', 'asc')
            ->paginate(10);

        return view('admin.schedules.pending', compact('schedules'));
    } */

    public function todaySchedules()
    {
        $today = Carbon::today();

        $schedules = Schedule::with(['student', 'file'])
            ->whereDate('preferred_date', $today) 
            ->whereIn('status', ['approved', 'completed'])
            ->orderBy('preferred_time', 'asc') 
            ->paginate(10);

        return view('admin.schedules.today', compact('schedules'));
    }

    public function weeklySchedules(Request $request)
    {
        $selectedDay = $request->query('day');

        $query = Schedule::with(['student', 'file'])
            ->whereBetween('preferred_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->whereIn('status', ['approved', 'completed'])
            ->orderBy('preferred_date', 'asc')
            ->orderBy('preferred_time', 'asc');

        if ($selectedDay) {
            $query->whereDate('preferred_date', Carbon::parse($selectedDay));
        }

        $schedules = $query->paginate(10)->withQueryString();

        // Count the number of approved schedules per day for the weekly summary
        $weeklyCounts = Schedule::whereBetween('preferred_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->whereIn('status', ['approved', 'completed'])
            ->selectRaw('preferred_date, COUNT(*) as count')
            ->groupBy('preferred_date')
            ->pluck('count', 'preferred_date');

        return view('admin.schedules.weekly', compact('schedules', 'selectedDay', 'weeklyCounts'));
    }

    public function completedSchedules(Request $request)
    {
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::today()->subDays(7);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::today();
        
        $schedules = Schedule::with(['student', 'file'])
            ->where('status', 'completed')
            ->whereBetween('completed_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->orderBy('completed_at', 'desc')
            ->paginate(10)
            ->withQueryString();
            
        return view('admin.schedules.completed', compact('schedules', 'startDate', 'endDate'));
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
            'rejected_at' => now()
        ]);

        return redirect()->back()->with('success', 'Schedule rejected successfully.');
    }
    
    public function complete(Request $request, Schedule $schedule)
{
    $request->validate([
        'completion_notes' => 'nullable|string|max:255'
    ]);

    $schedule->update([
        'status' => 'completed',
        'completed_at' => now(), // This saves current date+time
        'remarks' => $request->completion_notes ?? null
    ]);

    return back()->with('success', 'Request marked as completed');
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