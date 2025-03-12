<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class DashboardController extends Controller
{
    public function index()
{
    $student = Auth::guard('student')->user();

    // Get only pending requests
    $pendingSchedules = Schedule::where('student_id', $student->id)
        ->where('status', 'pending')
        ->orderBy('preferred_date', 'asc')
        ->get();

    // Get rejected requests with a reason
    $rejectedSchedules = Schedule::where('student_id', $student->id)
        ->where('status', 'rejected')
        ->whereNotNull('remarks') // Ensure it has a rejection reason
        ->orderBy('preferred_date', 'desc')
        ->get();

    return view('student.dashboard', compact('pendingSchedules', 'rejectedSchedules'));
}

    public function studentHistory()
    {
        $student = Auth::guard('student')->user();
    
        // Fetch completed schedules (past requests)
        $completedSchedules = Schedule::where('student_id', $student->id)
            ->whereDate('preferred_date', '<', now()) // Only past requests
            ->orderBy('preferred_date', 'desc')
            ->get();
    
        return view('student.history', compact('completedSchedules'));
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