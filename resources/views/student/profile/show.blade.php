@extends('layouts.default')

@section('content')
<div class="flex justify-center items-center h-full">
    <div class="w-full max-w-3xl bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Profile Header with Avatar -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6">
            <div class="flex items-center space-x-4">
                <div class="bg-white p-1 rounded-full">
                    <div class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-2xl font-bold">
                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                    </div>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-white">{{ $student->first_name }} {{ $student->last_name }}</h2>
                    <p class="text-blue-100">{{ $student->course }} Student</p>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('message'))
            <div class="mx-6 mt-4 bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative">
                <span class="flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('message') }}
                </span>
            </div>
        @endif

        <div class="p-6">
            <!-- Personal Information Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-200">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex items-center mb-1">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                            </svg>
                            <label class="text-sm text-gray-600 font-medium">Student ID</label>
                            <span class="ml-1 text-xs bg-blue-100 text-blue-800 px-1 rounded">Verified</span>
                        </div>
                        <p class="px-4 py-2 bg-gray-50 rounded-lg border border-gray-100">{{ $student->student_id }}</p>
                    </div>
                    
                    <div>
                        <div class="flex items-center mb-1">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <label class="text-sm text-gray-600 font-medium">Name</label>
                        </div>
                        <p class="px-4 py-2 bg-gray-50 rounded-lg border border-gray-100">{{ $student->first_name }} {{ $student->last_name }}</p>
                    </div>
                </div>
            </div>

            <!-- Contact & Academic Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-200">Contact & Academic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex items-center mb-1">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <label class="text-sm text-gray-600 font-medium">Email</label>
                        </div>
                        <p class="px-4 py-2 bg-gray-50 rounded-lg border border-gray-100">{{ $student->email }}</p>
                    </div>
                    
                    <div>
                        <div class="flex items-center mb-1">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <label class="text-sm text-gray-600 font-medium">Contact Number</label>
                        </div>
                        <p class="px-4 py-2 bg-gray-50 rounded-lg border border-gray-100">{{ $student->contact_number }}</p>
                    </div>
                    
                    <div>
                        <div class="flex items-center mb-1">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <label class="text-sm text-gray-600 font-medium">Course</label>
                        </div>
                        <p class="px-4 py-2 bg-gray-50 rounded-lg border border-gray-100">{{ $student->course }}</p>
                    </div>
                </div>
            </div>

            <!-- Edit Button -->
            <div class="mt-8 flex justify-center">
                <a href="{{ route('student.profile.edit') }}" 
                   class="flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-md transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Edit Profile
                </a>
            </div>
        </div>
    </div>
</div>
@endsection