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
                <h2 class="mt-4 text-2xl font-extrabold text-white relative z-10">Email Verification</h2>
                <p class="text-blue-200 text-sm relative z-10">A verification link has been sent to your email</p>
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

                <div class="text-center text-gray-600 mb-6">
                    <p>Please check your email for a verification link. If you didn't receive the email, you can request another one.</p>
                </div>

                <form method="POST" action="{{ route('verification.resend') }}" class="space-y-6">
                    @csrf
                    <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-blue-200 group-hover:text-blue-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </span>
                        Resend Verification Email
                    </button>
                </form>

                <!-- Logout Option -->
                <div class="mt-8 text-center border-t border-gray-200 pt-6">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-blue-600 hover:text-blue-800 transition duration-200 hover:underline">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection