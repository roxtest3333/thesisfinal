<?php

    namespace App\Http\Controllers\Student;

    use App\Http\Controllers\Controller;
    use App\Models\Schedule;
    use Illuminate\Support\Facades\Auth;
    use App\Models\User;
    use Carbon\Carbon;

    class DashboardController extends Controller
    {
        public function index()
        {
            $student = Auth::guard('student')->user();

            // Get statistics for the dashboard
            $totalRequests = Schedule::where('student_id', $student->id)->count();
            
            // Get only pending requests grouped by file type
            $pendingSchedules = Schedule::with('file')
                ->where('student_id', $student->id)
                ->where('status', 'pending')
                ->orderBy('preferred_date', 'asc')
                ->get();
            
            $pendingCount = $pendingSchedules->count();

            // Get pending compliance requests (previously 'rejected')
            $pendingComplianceSchedules = Schedule::with('file')
    ->where('student_id', $student->id)
    ->where('status', 'rejected')
    ->whereNotNull('remarks')
    ->whereDoesntHave('followupRequest', function($query) {
        // Only consider valid follow-up requests that weren't cancelled
        $query->whereIn('status', ['pending', 'processing', 'ready', 'completed', 'compliance_addressed']);
    })
    ->orderBy('preferred_date', 'desc')
    ->get();
            
            $pendingComplianceCount = $pendingComplianceSchedules->count();

            // Get approved requests
            $approvedCount = Schedule::where('student_id', $student->id)
                ->where('status', 'approved')
                ->count();

            // Get recent activity (last 5 status changes)
            $recentActivity = Schedule::with('file')
                ->where('student_id', $student->id)
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();

            // Group pending requests by document type
            $pendingByType = $pendingSchedules->groupBy('file.file_name');

            return view('student.dashboard', compact(
                'pendingSchedules', 
                'pendingComplianceSchedules',
                'totalRequests',
                'pendingCount',
                'approvedCount',
                'pendingComplianceCount',
                'recentActivity',
                'pendingByType'
            ));
        }

        public function studentHistory()
        {
            $student = Auth::guard('student')->user();

            // Fetch all schedule requests for the student, ordered by date (most recent first)
            $allSchedules = Schedule::with('file')
                ->where('student_id', $student->id)
                ->orderBy('preferred_date', 'desc')
                ->paginate(10); 

            return view('student.history', compact('allSchedules'));
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

        public function sortRequests(string $sortBy = 'date')
        {
            $student = Auth::guard('student')->user();
            
            $query = Schedule::with('file')
                ->where('student_id', $student->id);
                
            switch ($sortBy) {
                case 'type':
                    $query->join('files', 'schedules.file_id', '=', 'files.id')
                        ->orderBy('files.file_name');
                    break;
                case 'status':
                    $query->orderBy('status');
                    break;
                case 'date':
                default:
                    $query->orderBy('preferred_date', 'desc');
                    break;
            }
            
            $allSchedules = $query->select('schedules.*')->paginate(10);
            
            return view('student.history', compact('allSchedules'))
                ->with('sortBy', $sortBy);
        }
    }