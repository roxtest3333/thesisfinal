<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Student;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get the requested month or default to current month
        $selectedMonth = request()->get('calendar_month') 
            ? Carbon::parse(request()->get('calendar_month'))
            : Carbon::now();
        
        // Calculate first day of month and days in month
        $firstDayOfMonth = $selectedMonth->copy()->startOfMonth()->dayOfWeek;
        $daysInMonth = $selectedMonth->daysInMonth;
        $currentMonth = $selectedMonth->format('F Y');
        
        // Get counts for each day of the month
        $monthlyCounts = [];
        
        // Initialize with 0 counts
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $monthlyCounts[$i] = 0;
        }
        
        // Get actual counts from database
        $counts = Schedule::whereYear('preferred_date', $selectedMonth->year)
            ->whereMonth('preferred_date', $selectedMonth->month)
            ->whereIn('status', ['approved', 'completed'])
            ->selectRaw('DAY(preferred_date) as day, COUNT(*) as count')
            ->groupBy('day')
            ->pluck('count', 'day');
        
        // Merge with initialized array
        foreach ($counts as $day => $count) {
            $monthlyCounts[$day] = $count;
        }
        
        // Generate months for dropdown (current year ±1)
        $calendarMonths = collect();
        $currentYear = Carbon::now()->year;
        
        for ($year = $currentYear - 1; $year <= $currentYear + 1; $year++) {
            for ($month = 1; $month <= 12; $month++) {
                $date = Carbon::create($year, $month, 1);
                $calendarMonths->push([
                    'value' => $date->format('Y-m'),
                    'label' => $date->format('F Y')
                ]);
            }
        }
        
        // Your existing dashboard data
        $pendingRequests = Schedule::where('status', 'pending')->count();
        $todayAppointments = Schedule::whereDate('preferred_date', today())
            ->whereIn('status', ['approved', 'completed'])
            ->count();
        $weeklyAppointments = Schedule::whereBetween('preferred_date', [
            Carbon::now()->startOfWeek(), 
            Carbon::now()->endOfWeek()
        ])
        ->whereIn('status', ['approved', 'completed'])
        ->count();
        
        $weeklyCounts = Schedule::whereBetween('preferred_date', [
            Carbon::now()->startOfWeek(), 
            Carbon::now()->endOfWeek()
        ])
        ->whereIn('status', ['approved', 'completed'])
        ->selectRaw('preferred_date, COUNT(*) as count')
        ->groupBy('preferred_date')
        ->pluck('count', 'preferred_date');
        
        return view('admin.dashboard', compact(
            'pendingRequests',
            'todayAppointments',
            'weeklyAppointments',
            'weeklyCounts',
            'monthlyCounts',
            'firstDayOfMonth',
            'daysInMonth',
            'currentMonth',
            'selectedMonth',
            'calendarMonths'
        ));
    }

    public function todayAppointments()
    {
        $schedules = Schedule::with(['student', 'file'])
            ->whereDate('preferred_date', now())
            ->where('status', 'approved')
            ->orderBy('preferred_time')
            ->paginate(15);

        return view('admin.schedules.today', compact('schedules'));
    }

    public function weeklyAppointments()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $currentWeek = $startOfWeek->format('M d') . ' - ' . $endOfWeek->format('M d, Y');
        
        $schedules = Schedule::with(['student', 'file'])
            ->whereBetween('preferred_date', [$startOfWeek, $endOfWeek])
            ->where('status', 'approved')
            ->orderBy('preferred_date')
            ->orderBy('preferred_time')
            ->paginate(20);

        // Group schedules by day for easier display
        $groupedSchedules = $schedules->groupBy(function ($schedule) {
            return Carbon::parse($schedule->preferred_date)->format('l, M d'); // Format: Monday, Jan 01
        });

        return view('admin.schedules.weekly', compact('groupedSchedules', 'currentWeek', 'schedules'));
    }

    public function monthlyAppointments()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $currentMonth = Carbon::now()->format('F Y');
        
        $schedules = Schedule::with(['student', 'file'])
            ->whereBetween('preferred_date', [$startOfMonth, $endOfMonth])
            ->where('status', 'approved')
            ->orderBy('preferred_date')
            ->orderBy('preferred_time')
            ->paginate(30);

        // Group schedules by day for easier display
        $groupedSchedules = $schedules->groupBy(function ($schedule) {
            return Carbon::parse($schedule->preferred_date)->format('F d, Y'); // Format: January 01, 2025
        });

        return view('admin.schedules.monthly', compact('groupedSchedules', 'currentMonth', 'schedules'));
    }
}