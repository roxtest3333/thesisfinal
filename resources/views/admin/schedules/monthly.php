@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Monthly Schedule: {{ $currentMonth }}</h2>
        <div class="flex space-x-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
            </a>
           <!--  <button onclick="window.print()" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-print mr-1"></i> Print Schedule
            </button> -->
        </div>
    </div>
    
    @if ($schedules->isEmpty())
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar-times text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        No approved appointments scheduled for this month.
                    </p>
                </div>
            </div>
        </div>
    @else
        <!-- Monthly Calendar View -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <div class="grid grid-cols-7 gap-1 text-center mb-2">
                <div class="text-sm font-semibold">Sun</div>
                <div class="text-sm font-semibold">Mon</div>
                <div class="text-sm font-semibold">Tue</div>
                <div class="text-sm font-semibold">Wed</div>
                <div class="text-sm font-semibold">Thu</div>
                <div class="text-sm font-semibold">Fri</div>
                <div class="text-sm font-semibold">Sat</div>
            </div>
            
            <div class="grid grid-cols-7 gap-1">
                @php
                    $startOfMonth = \Carbon\Carbon::now()->startOfMonth();
                    $endOfMonth = \Carbon\Carbon::now()->endOfMonth();
                    $daysInMonth = $endOfMonth->day;
                    $startDay = $startOfMonth->dayOfWeek; // 0 (Sunday) to 6 (Saturday)
                    
                    // Group appointments by date for the calendar
                    $appointmentsByDate = [];
                    foreach ($schedules as $schedule) {
                        $date = \Carbon\Carbon::parse($schedule->preferred_date)->format('Y-m-d');
                        if (!isset($appointmentsByDate[$date])) {
                            $appointmentsByDate[$date] = 0;
                        }
                        $appointmentsByDate[$date]++;
                    }
                @endphp
                
                <!-- Empty cells before the first day of month -->
                @for ($i = 0; $i < $startDay; $i++)
                    <div class="h-24 bg-gray-50 rounded"></div>
                @endfor
                
                <!-- Days of the month -->
                @for ($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $currentDate = $startOfMonth->copy()->addDays($day - 1);
                        $dateStr = $currentDate->format('Y-m-d');
                        $hasAppointments = isset($appointmentsByDate[$dateStr]) && $appointmentsByDate[$dateStr] > 0;
                        $isToday = $currentDate->isToday();
                    @endphp
                    
                    <div class="h-24 p-1 border {{ $isToday ? 'border-blue-400 bg-blue-50' : 'border-gray-200' }} rounded relative hover:bg-gray-50 transition-colors">
                        <div class="absolute top-1 left-2 {{ $isToday ? 'font-bold text-blue-700' : 'text-gray-700' }}">
                            {{ $day }}
                        </div>
                        
                        @if($hasAppointments)
                            <a href="#day-{{ $dateStr }}" class="absolute bottom-1 right-1 h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center hover:bg-blue-600 transition-colors">
                                <span class="text-white font-bold text-xs">{{ $appointmentsByDate[$dateStr] }}</span>
                            </a>
                        @endif
                    </div>
                @endfor
            </div>
        </div>
        
        <!-- Detailed Day-by-Day Schedule -->
        @foreach ($groupedSchedules as $date => $daySchedules)
        <div id="day-{{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}" class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-gray-50 py-3 px-4 border-b border-gray-200">
                    <div class="text-lg font-medium text-gray-900">
                        {{ $date }} <span class="text-sm text-gray-500 ml-2">({{ count($daySchedules) }} appointments)</span>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($daySchedules as $schedule)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ Carbon\Carbon::parse($schedule->preferred_time)->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                                <span class="text-gray-500 font-semibold">{{ substr($schedule->student->first_name, 0, 1) }}{{ substr($schedule->student->last_name, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $schedule->student->first_name }} {{ $schedule->student->last_name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $schedule->student->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $schedule->file->file_name }}</div>
                                        <div class="text-xs text-gray-500">{{ Str::limit($schedule->purpose, 30) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.schedules.show', $schedule->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

        @if ($schedules->hasPages())
            <div class="mt-4">
                {{ $schedules->links() }}
            </div>
        @endif
    @endif

    <script>
        // Smooth scroll to the specific day when clicking on the calendar
        document.addEventListener('DOMContentLoaded', function() {
            const urlHash = window.location.hash;
            if (urlHash) {
                const element = document.querySelector(urlHash);
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    </script>
</div>
@endsection