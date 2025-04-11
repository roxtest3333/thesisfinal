@extends('layouts.default')

@section('content')
    <div class="container mx-auto max-w-6xl px-4">
        <!-- Admin Dashboard Header -->
        <div class="text-center mt-4 mb-8 fade-in">
            <h2 class="text-3xl font-bold dashboard-title">
                Admin Dashboard
            </h2>
        </div>

        <!-- Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('admin.schedules.index') }}" 
               class="group rounded-xl bg-white bg-opacity-50 backdrop-blur-sm p-6 shadow-md transition-all hover:bg-white/30 fade-in pending-requests-card">
                <h5 class="text-lg text-black drop-shadow-md">
                    Pending Requests
                </h5>
                <h2 class="text-4xl font-bold text-black drop-shadow-lg mt-2 pending-requests count-up">
                    {{ $pendingRequests }}
                </h2>
            </a>

            <a href="{{ route('admin.schedules.today') }}" 
               class="group rounded-xl bg-white bg-opacity-50 backdrop-blur-sm p-6 shadow-md transition-all hover:bg-white/30 fade-in today-appointments-card">
                <h5 class="text-lg text-black drop-shadow-md">
                    Today's Appointments
                </h5>
                <h2 class="text-4xl font-bold text-black drop-shadow-lg mt-2 today-appointments count-up">
                    {{ $todayAppointments }}
                </h2>
            </a>

            <a href="{{ route('admin.schedules.weekly') }}" 
               class="group rounded-xl bg-white bg-opacity-50 backdrop-blur-sm p-6 shadow-md transition-all hover:bg-white/30 fade-in weekly-appointments-card">
                <h5 class="text-lg text-black drop-shadow-md">
                    This Week's Appointments
                </h5>
                <h2 class="text-4xl font-bold text-black drop-shadow-lg mt-2 weekly-appointments count-up">
                    {{ $weeklyAppointments }}
                </h2>
            </a>
        </div>

        

        <div class="flex flex-col md:flex-row w-full gap-3 mb-8">
            @foreach($weeklyCounts as $day => $count)
                <div class="group rounded-xl bg-white bg-opacity-50 backdrop-blur-sm p-4 shadow-md transition-all hover:bg-white/30 flex-1 fade-in">
                    <h4 class="text-sm text-black drop-shadow-md">
                        {{ $day }}
                    </h4>
                    <p class="text-2xl font-bold text-black drop-shadow-lg mt-2 weekly-count count-up">
                        {{ $count }}
                    </p>
                </div>
            @endforeach
        </div>

        

        <!-- Monthly Calendar -->
<div class="text-center mb-6 fade-in">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold dashboard-title">
            Monthly Calendar: {{ $currentMonth }}
        </h2>
        
        <div class="flex items-center space-x-2">
            <a href="?calendar_month={{ $selectedMonth->copy()->subMonth()->format('Y-m') }}" 
               class="px-3 py-1 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors text-sm">
                &larr;
            </a>
            
            <form method="GET" action="">
                <select name="calendar_month" onchange="this.form.submit()" 
                        class="px-3 py-1 border rounded-lg bg-white text-sm">
                    @foreach($calendarMonths as $month)
                        <option value="{{ $month['value'] }}" 
                            {{ $selectedMonth->format('Y-m') === $month['value'] ? 'selected' : '' }}>
                            {{ $month['label'] }}
                        </option>
                    @endforeach
                </select>
            </form>
            
            <a href="?calendar_month={{ $selectedMonth->copy()->addMonth()->format('Y-m') }}" 
               class="px-3 py-1 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors text-sm">
                &rarr;
            </a>
            
           
        </div>
    </div>
</div>

<div class="bg-white bg-opacity-50 backdrop-blur-sm p-6 rounded-xl shadow-md mb-8 fade-in">
    <div class="grid grid-cols-7 gap-2 text-center">
        <div class="text-sm font-semibold">Sun</div>
        <div class="text-sm font-semibold">Mon</div>
        <div class="text-sm font-semibold">Tue</div>
        <div class="text-sm font-semibold">Wed</div>
        <div class="text-sm font-semibold">Thu</div>
        <div class="text-sm font-semibold">Fri</div>
        <div class="text-sm font-semibold">Sat</div>
        
        @php
            $dayOfWeek = $firstDayOfMonth;
        @endphp
        
        @for ($i = 0; $i < $dayOfWeek; $i++)
            <div class="h-16 p-1"></div>
        @endfor
        
        @for ($day = 1; $day <= $daysInMonth; $day++)
            @php
                $currentDate = $selectedMonth->copy()->setDay($day);
                $isToday = $currentDate->isToday();
                $hasAppointments = $monthlyCounts[$day] > 0;
            @endphp
            
            <div class="h-16 p-1 border border-gray-200 rounded relative transition-all 
                {{ $hasAppointments ? 'bg-blue-50 hover:bg-blue-100' : '' }}
                {{ $isToday ? 'border-2 border-blue-500' : '' }}">
                
                <div class="absolute top-1 left-1 text-xs {{ $isToday ? 'font-bold text-blue-600' : '' }}">
                    {{ $day }}
                </div>
                
                @if($hasAppointments)
                    <div class="absolute bottom-1 right-1 h-6 w-6 rounded-full bg-blue-500 flex items-center justify-center">
                        <span class="text-white text-xs font-bold">{{ $monthlyCounts[$day] }}</span>
                    </div>
                @endif
                
                @if($hasAppointments)
                    <a href="{{ route('admin.schedules.index', [
                        'start_date' => $currentDate->format('Y-m-d'),
                        'end_date' => $currentDate->format('Y-m-d'),
                        'status' => 'approved,completed'
                    ]) }}" 
                       class="absolute inset-0 z-10" title="View appointments for this day">
                    </a>
                @endif
            </div>
            @php $dayOfWeek = ($dayOfWeek + 1) % 7; @endphp
        @endfor
    </div>
</div>

<div class="text-center mb-8 fade-in">
    <a href="{{ route('admin.schedules.monthly') }}" 
       class="inline-block px-6 py-3 bg-blue-700 text-white font-bold rounded-lg hover:bg-blue-500 transition-colors view-monthly-btn">
        View Full Monthly Schedule
    </a>
</div>
    </div>

    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Function to animate counting up
            function animateValue(element, start, end, duration) {
                let startTimestamp = null;
                const step = (timestamp) => {
                    if (!startTimestamp) startTimestamp = timestamp;
                    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                    element.textContent = Math.floor(progress * (end - start) + start);
                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    }
                };
                window.requestAnimationFrame(step);
            }

            // Animate the card values
            const pendingRequests = document.querySelector('.pending-requests');
            const todayAppointments = document.querySelector('.today-appointments');
            const weeklyAppointments = document.querySelector('.weekly-appointments');
            const weeklyCount = document.querySelectorAll('.weekly-count');

            if (pendingRequests) {
                animateValue(pendingRequests, 0, {{ $pendingRequests }}, 1000);
            }

            if (todayAppointments) {
                animateValue(todayAppointments, 0, {{ $todayAppointments }}, 1000);
            }

            if (weeklyAppointments) {
                animateValue(weeklyAppointments, 0, {{ $weeklyAppointments }}, 1000);
            }

            weeklyCount.forEach((element, index) => {
                const count = parseInt(element.textContent);
                animateValue(element, 0, count, 1000);
            });
        });
    </script>
@endsection