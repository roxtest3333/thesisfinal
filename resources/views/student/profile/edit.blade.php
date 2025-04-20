{{-- resources\views\student\profile\edit.blade.php --}}

@extends('layouts.default')

@section('content')
<div class="py-8">
    <div class="mx-auto bg-white shadow-lg rounded-lg overflow-hidden max-w-4xl">
        <!-- Form Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <h2 class="text-2xl font-bold text-white">Edit Profile</h2>
            </div>
            <p class="text-blue-100 mt-1">Update your personal information</p>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('student.profile.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Personal Information Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-200">Personal Information</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                First Name
                            </label>
                            <input type="text" name="first_name" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500" value="{{ old('first_name', $student->first_name) }}" required>
                            @error('first_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Middle Name
                            </label>
                            <input type="text" name="middle_name" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500" value="{{ old('middle_name', $student->middle_name ?? '') }}">
                            @error('middle_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Last Name
                            </label>
                            <input type="text" name="last_name" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500" value="{{ old('last_name', $student->last_name) }}" required>
                            @error('last_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Sex
                            </label>
                            <select name="sex" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('sex', $student->sex ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('sex', $student->sex ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('sex')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Birthday
                            </label>
                            <input type="date" name="birthday" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500" value="{{ old('birthday', $student->birthday ? $student->birthday->format('Y-m-d') : '') }}" required>
                            @error('birthday')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Birthplace
                            </label>
                            <input type="text" name="birthplace" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500" value="{{ old('birthplace', $student->birthplace ?? '') }}" required>
                            @error('birthplace')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact & Academic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-200">Contact & Academic Information</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Email
                            </label>
                            <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500" value="{{ old('email', $student->email) }}" required>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                Home Address
                            </label>
                            <textarea name="home_address" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500" rows="3" required>{{ old('home_address', $student->home_address ?? '') }}</textarea>
                            @error('home_address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    Course
                                </label>
                                <select name="course" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="">Select Course</option>
                                    <option value="BAT" {{ old('course', $student->course) == 'BAT' ? 'selected' : '' }}>Bachelor of Arts in Tourism</option>
                                    <option value="BSA" {{ old('course', $student->course) == 'BSA' ? 'selected' : '' }}>Bachelor of Science in Accountancy</option>
                                    <option value="BEED" {{ old('course', $student->course) == 'BEED' ? 'selected' : '' }}>Bachelor in Elementary Education</option>
                                    <option value="BSED" {{ old('course', $student->course) == 'BSED' ? 'selected' : '' }}>Bachelor in Secondary Education</option>
                                    <option value="BSCS" {{ old('course', $student->course) == 'BSCS' ? 'selected' : '' }}>Bachelor of Science in Computer Science</option>
                                    <option value="BSHM" {{ old('course', $student->course) == 'BSHM' ? 'selected' : '' }}>Bachelor of Science in Hospitality Management</option>
                                </select>
                                @error('course')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    Contact Number
                                </label>
                                <input type="text" name="contact_number" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500" value="{{ old('contact_number', $student->contact_number) }}" placeholder="09XXXXXXXXX" required>
                                @error('contact_number')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Password Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-200">Security</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                New Password <span class="text-xs text-gray-500 ml-1">(Optional)</span>
                            </label>
                            <input type="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Must be at least 8 characters</p>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                Confirm Password
                            </label>
                            <input type="password" name="password_confirmation" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <button type="submit" class="sm:flex-1 flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-md transition duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Profile
                    </button>
                    <a href="{{ route('student.profile.show') }}" class="sm:flex-1 flex items-center justify-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg shadow-sm transition duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection