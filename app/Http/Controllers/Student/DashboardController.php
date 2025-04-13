<?php

    namespace App\Http\Controllers\Student;

    use App\Http\Controllers\Controller;
    use App\Models\Schedule;
    use App\Models\File;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Carbon\Carbon;

    class DashboardController extends Controller
    {
        public function index()
{
    $student = Auth::guard('student')->user();

    // Get total requests (can be used for stats)
    $totalRequests = Schedule::where('student_id', $student->id)->count();

    // Pending requests
    $pendingSchedules = Schedule::with('file')
        ->where('student_id', $student->id)
        ->where('status', 'pending')
        ->orderBy('preferred_date', 'asc')
        ->get();
    $pendingCount = $pendingSchedules->count();
    $pendingByType = $pendingSchedules->groupBy('file.file_name');

    // Pending compliance (rejected with remarks, no followup)
    $pendingComplianceSchedules = Schedule::with('file')
        ->where('student_id', $student->id)
        ->where('status', 'rejected')
        ->whereNotNull('remarks')
        ->whereDoesntHave('followupRequest', function($query) {
            $query->whereIn('status', ['pending', 'processing', 'ready', 'completed', 'compliance_addressed']);
        })
        ->orderBy('preferred_date', 'desc')
        ->get();
    $pendingComplianceCount = $pendingComplianceSchedules->count();

    $acceptedSchedules = Schedule::with('file')
    ->where('student_id', $student->id)
    ->where('status', 'approved')
    ->where('updated_at', '>=', now()->subDays(30)) // Only last 30 days
    ->orderBy('preferred_date', 'desc')
    ->get();

    $acceptedCount = $acceptedSchedules->count();
    $acceptedByType = $acceptedSchedules->groupBy('file.file_name');

    // Recent activity (optional)
    $recentActivity = Schedule::with('file')
        ->where('student_id', $student->id)
        ->orderBy('updated_at', 'desc')
        ->take(5)
        ->get();

    return view('student.dashboard', compact(
        'pendingSchedules', 
        'pendingComplianceSchedules',
        'acceptedSchedules',
        'totalRequests',
        'pendingCount',
        'acceptedCount',
        'pendingComplianceCount',
        'recentActivity',
        'pendingByType',
        'acceptedByType'
    ));
}


public function studentHistory(Request $request)
{
    $student = Auth::guard('student')->user();
    
    $query = Schedule::with('file')
        ->where('student_id', $student->id)
        // Always exclude compliance_addressed status by default
        ->where('status', '!=', 'compliance_addressed');
    
    // Status filter - simplified
    if ($request->filled('status') && $request->status != 'all') {
        $query->where('status', $request->status);
    }
    
    // Document type filter
    if ($request->filled('document_type') && $request->document_type != 'all') {
        $query->whereHas('file', function($q) use ($request) {
            $q->where('file_name', $request->document_type);
        });
    }
    
    // Get document types for dropdown
    $documentTypes = File::whereHas('schedules', function($q) use ($student) {
        $q->where('student_id', $student->id)
          ->where('status', '!=', 'compliance_addressed');
    })->pluck('file_name')->unique();
    
    // Sorting
    $sortBy = $request->input('sort', 'preferred_date'); // Default to date
    $direction = $request->input('direction', 'desc'); // Default to descending

    $validSortFields = ['preferred_date', 'file_name', 'status'];
    $sortBy = in_array($sortBy, $validSortFields) ? $sortBy : 'preferred_date';
    
    if ($sortBy === 'file_name') {
        $query->join('files', 'schedules.file_id', '=', 'files.id')
              ->orderBy('files.file_name', $direction);
    } else {
        $query->orderBy($sortBy, $direction);
    }
    
    $allSchedules = $query->paginate(10);
    
    return view('student.history', [
        'allSchedules' => $allSchedules,
        'documentTypes' => $documentTypes,
        'currentStatus' => $request->status ?? 'all',
        'currentDocumentType' => $request->document_type ?? 'all',
        'sortBy' => $sortBy,
        'direction' => $direction
    ]);
}



        public function cancelRequest($id)
        {
            $student = Auth::guard('student')->user();
            
            // Find the schedule request and ensure it belongs to the logged-in student
            $schedule = Schedule::where('id', $id)
                ->where('student_id', $student->id)
                ->where('status', 'pending')
                ->first();

            if (!$schedule) {
                return back()->with('error', 'You can only cancel pending requests.');
            }

            $schedule->delete(); // Remove the request

            return back()->with('message', 'Schedule request cancelled successfully.');
        }

    }