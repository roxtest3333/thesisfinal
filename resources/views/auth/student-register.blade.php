@extends('layouts.default')

@section('main-class', 'min-h-[30vh]')

@section('content')
<div class="flex justify-center items-center h-full">
    <!-- Form Container with Blur Effect and Animation -->
    <div class="w-full max-w-3xl bg-white bg-opacity-70 backdrop-blur-md shadow-xl rounded-xl p-8 sm:px-10 animate-fadeIn">
        <div class="text-center mb-8">
            <div class="inline-block p-3 rounded-full bg-sky-100 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-800 drop-shadow-md">Create Your Student Account</h2>
            <p class="text-gray-600 mt-2">Fill in your details to get started</p>
        </div>

        <!-- Success Message -->
        @if(session('message'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6 animate-fadeIn">
                <p class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path>
                    </svg>
                    {{ session('message') }}
                </p>
            </div>
        @endif

        <!-- Registration Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Two-column layout for desktop -->
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Left Column: Personal Information -->
                <div class="space-y-6">
                    <h3 class="font-semibold text-gray-700 border-b pb-2">Personal Information</h3>
                    
                    <!-- Student ID -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Student ID</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="student_id" value="{{ old('student_id') }}" 
                                class="w-full pl-10 pr-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-colors" 
                                required maxlength="9" placeholder="XX-XXXXXX">
                        </div>
                        
                        @error('student_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- First Name -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">First Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg> 
                            </div>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" 
                                class="w-full pl-10 pr-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-colors" 
                                required>
                        </div>
                        @error('first_name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Last Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" 
                                class="w-full pl-10 pr-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-colors" 
                                required>
                        </div>
                        @error('last_name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column: Contact & Course Information -->
                <div class="space-y-6">
                    <h3 class="font-semibold text-gray-700 border-b pb-2">Contact & Program</h3>
                    
                    <!-- Email -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" 
                                class="w-full pl-10 pr-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-colors" 
                                required>
                        </div>
                        @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Course Dropdown -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Course</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                                </svg>
                            </div>
                            <select name="course" class="w-full pl-10 pr-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-colors appearance-none" required>
                                <option value="">Select Course</option>
                                <option value="BAT" {{ old('course') == 'BAT' ? 'selected' : '' }}>BAT</option>
                                <option value="BSA" {{ old('course') == 'BSA' ? 'selected' : '' }}>BSA</option>
                                <option value="BEED" {{ old('course') == 'BEED' ? 'selected' : '' }}>BEED</option>
                                <option value="BSED" {{ old('course') == 'BSED' ? 'selected' : '' }}>BSED</option>
                                <option value="BSCS" {{ old('course') == 'BSCS' ? 'selected' : '' }}>BSCS</option>
                                <option value="BSHM" {{ old('course') == 'BSHM' ? 'selected' : '' }}>BSHM</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                </svg>
                            </div>
                        </div>
                        @error('course')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Number -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Contact Number</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                </svg>
                            </div>
                            <input type="text" name="contact_number" value="{{ old('contact_number') }}" 
                                class="w-full pl-10 pr-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-colors" 
                                required maxlength="11" placeholder="09XXXXXXXXX">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Format: 11 digits (e.g., 09123456789)</p>
                        @error('contact_number')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Security Section - Full Width -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="font-semibold text-gray-700 mb-4">Create Your Password</h3>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Password -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="password" name="password" id="password"
                                class="w-full pl-10 pr-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-colors" 
                                required>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Must be at least 8 characters</p>
                        @error('password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Confirm Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="password" name="password_confirmation" 
                                class="w-full pl-10 pr-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-colors" 
                                required>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Re-enter your password</p>
                        @error('password_confirmation')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Terms and Conditions (Optional) -->
            <div class="flex items-center mt-4">
                <input type="checkbox" id="terms" name="terms" class="h-4 w-4 text-sky-600 focus:ring-sky-500 border-gray-300 rounded">
                <label for="terms" class="ml-2 block text-sm text-gray-700">
                    I agree to the <a href="#" class="text-sky-600 hover:text-sky-800">Terms and Conditions</a> and <a href="#" class="text-sky-600 hover:text-sky-800">Privacy Policy</a>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-gradient-to-r from-sky-500 to-sky-600 text-white font-bold py-3 rounded-lg hover:from-sky-600 hover:to-sky-700 transition duration-300 transform hover:-translate-y-0.5 shadow-md">
                Create Account
            </button>

            <!-- Login Link -->
            <div class="text-center mt-4">
                <p class="text-gray-600">Already have an account? 
                    <a href="{{ route('login') }}" class="text-sky-600 font-semibold hover:text-sky-800 transition duration-200">
                        Sign In
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript to format Student ID and enhance UX -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Student ID formatting
        const studentIdInput = document.querySelector('input[name="student_id"]');
        studentIdInput.addEventListener("input", function() {
    // Remove all non-digits and existing hyphens
    let value = this.value.replace(/[^\d-]/g, ""); 
    
    // If user tries to type beyond format, stop them
    if (value.replace(/-/g, "").length > 9) {
        this.value = value.slice(0, 12); // 11 because of hyphen positions
        return;
    }
    
    // Auto-insert hyphen after 2 digits
    if (value.length > 2 && !value.includes("-")) {
        this.value = value.slice(0, 2) + "-" + value.slice(2);
    }
    
    // Prevent multiple hyphens
    if ((value.match(/-/g) || []).length > 1) {
        this.value = value.slice(0, value.lastIndexOf("-")) + 
                    value.slice(value.lastIndexOf("-") + 1);
    }
});
        // Password strength indicator (basic example)
        const passwordInput = document.getElementById('password');
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            // Basic strength check (you can expand this)
            if (password.length >= 8) {
                this.classList.add('border-green-500');
                this.classList.remove('border-red-500');
            } else {
                this.classList.add('border-red-500');
                this.classList.remove('border-green-500');
            }
        });
    });
</script>
@endsection