<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\File;
use App\Models\Student;
use App\Models\SchoolYear;
use App\Models\Semester;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $files = File::all();
        $schoolYears = SchoolYear::all();
        $semesters = Semester::all();

        $query = Schedule::with(['student', 'file', 'schoolYear'])
            ->join('semesters', 'schedules.semester_id', '=', 'semesters.id')
            ->join('students', 'schedules.student_id', '=', 'students.id')
            ->select('schedules.*', 'semesters.name as semester_name', 'students.id as student_id');

        //  Search Student Name
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('students.first_name', 'like', "%{$request->search}%")
                  ->orWhere('students.last_name', 'like', "%{$request->search}%");
            });
        }

        // Apply Filters
        $query->when($request->file_id, fn($q) => $q->where('schedules.file_id', $request->file_id))
              ->when($request->school_year_id, fn($q) => $q->where('schedules.school_year_id', $request->school_year_id))
              ->when($request->semester_id, fn($q) => $q->where('schedules.semester_id', $request->semester_id))
              ->when($request->status, fn($q) => $q->where('schedules.status', $request->status));

        // Sorting & Pagination
        $schedules = $query->orderBy('students.last_name', 'asc')
                         ->orderBy('schedules.preferred_date', 'desc')
                         ->paginate(10)
                         ->withQueryString();

        return view('admin.reports.index', compact('schedules', 'files', 'schoolYears', 'semesters'));
    }

    public function studentReport(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $schoolYears = SchoolYear::all();
        $semesters = Semester::all();

        $studentSchedules = Schedule::with(['file', 'semester', 'schoolYear'])
            ->where('student_id', $id)
            ->when($request->school_year_id, fn($query) => $query->where('school_year_id', $request->school_year_id))
            ->when($request->semester_id, fn($query) => $query->where('semester_id', $request->semester_id))
            ->when($request->manual_school_year, fn($query) => $query->where('manual_school_year', 'LIKE', "%{$request->manual_school_year}%"))
            ->when($request->manual_semester, fn($query) => $query->where('manual_semester', 'LIKE', "%{$request->manual_semester}%"))
            ->when($request->copies, fn($query) => $query->where('copies', $request->copies))
            ->when($request->status, fn($query) => $query->where('status', $request->status))
            ->selectRaw('student_id, file_id, semester_id, school_year_id, manual_school_year, manual_semester, copies, COUNT(*) as request_count, MAX(created_at) as latest_request, MAX(completed_at) as completion_date, status')
            ->groupBy('student_id', 'file_id', 'semester_id', 'school_year_id', 'manual_school_year', 'manual_semester', 'copies', 'status')
            ->paginate(10);

        return view('admin.reports.student', compact('student', 'studentSchedules', 'schoolYears', 'semesters'));
    }

    public function archivedReports()
    {
        $sevenDaysAgo = now()->subDays(7);

        $archivedSchedules = Schedule::with(['student', 'file'])
            ->join('students', 'schedules.student_id', '=', 'students.id')
            ->select('schedules.*', 'students.id as student_id')
            ->where('schedules.created_at', '<', $sevenDaysAgo)
            ->orderBy('students.last_name', 'asc')
            ->paginate(10);

        return view('admin.reports.archived', compact('archivedSchedules'));
    }

    public function todayAppointments()
    {
        $today = Carbon::today(); // Get the current date

        $schedules = Schedule::with(['student', 'file'])
            ->join('students', 'schedules.student_id', '=', 'students.id')
            ->select('schedules.*', 'students.id as student_id')
            ->whereDate('schedules.preferred_date', $today) // Filter for today's date
            ->where('schedules.status', 'approved') // Only approved schedules
            ->orderBy('students.last_name', 'asc')
            ->orderBy('schedules.preferred_time', 'asc') // Sort by time
            ->get();

        return view('admin.reports.today', compact('schedules'));
    }

    public function exportPDF()
    {
        // Implementation for PDF export
        // This would typically use a PDF library like dompdf, TCPDF, or Snappy
    }
}