@extends('layouts.default')

@section('content')
<div class="flex justify-center items-center min-h-[calc(100vh-24rem)] min-w-[30vw]">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden transform transition duration-500 hover:scale-105">
            <!-- Card Header with Logo -->
            <div class="bg-gray-800 p-6 text-center relative overflow-hidden">
                <div class="flex justify-center relative z-10">
                    <img src="{{ asset('images/prmsu-logo-big.png') }}" alt="PRMSU Logo" class="h-16 w-16 rounded-full border-4 border-white shadow-lg">
                </div>
                <h2 class="mt-4 text-2xl font-extrabold text-white relative z-10">Forgot Password</h2>
                <p class="text-blue-200 text-sm relative z-10">Enter your email to receive a password reset link</p>
            </div>
            
            <!-- Card Body -->
            <div class="p-8 bg-gradient-to-b from-white to-gray-50">
                @if (session('message'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6 animate-fadeIn">
                        <p class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path>
                            </svg>
                            {{ session('message') }}
                        </p>
                    </div>
                @endif

                <form method="POST" action="{{ route('student.password.email') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Email Field -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                            <input type="email" name="email" 
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                required autofocus>
                        </div>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-blue-200 group-hover:text-blue-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </span>
                            Send Reset Link
                        </button>
                    </div>
                </form>

                <!-- Back to Login Link -->
                <div class="mt-8 text-center border-t border-gray-200 pt-6">
                    <a href="{{ route('login') }}" class="inline-block font-medium text-blue-600 hover:text-blue-800 transition duration-200 hover:underline">
                        Back to Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection