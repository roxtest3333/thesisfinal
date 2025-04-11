@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Today's Approved Appointments</h2>
        <div class="flex space-x-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
            </a>
           
        </div>
    </div>
    
    @if ($schedules->isEmpty())
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar-times text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        No approved appointments scheduled for today.
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gray-50 py-3 px-4 border-b border-gray-200">
                <div class="text-lg font-medium text-gray-900">
                    {{ now()->format('l, F d, Y') }}
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($schedules as $schedule)
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
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Approved
                                    </span>
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

        @if ($schedules->hasPages())
            <div class="mt-4">
                {{ $schedules->links() }}
            </div>
        @endif
    @endif
</div>
@endsection