<!-- resources/views/landing.blade.php -->
@extends('layouts.default')

@section('content')
<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        AOS.init({ duration: 1000, once: true });
    });
</script>

<!-- Background with subtle gradient -->
<div class="bg-gradient-to-b from-white to-indigo-50 overflow-x-hidden">
    <div class="container mx-auto px-4 sm:px-6 py-8 md:py-12 lg:py-8 w-full">
        <!-- Hero Section - Improved Mobile Stacking -->
        <section class="flex flex-col-reverse md:flex-row items-center gap-8 md:gap-12 lg:gap-16">
            <!-- Text Content (Now comes first in DOM for better mobile flow) -->
            <div class="w-full md:w-1/2 text-center md:text-left" data-aos="fade-right">
                <!-- PRMSU Logo - Adjusted for mobile -->
                <div class="mb-4 md:mb-6">
                    <img 
                        src="{{ asset('images/prmsu-logo-big.png') }}" 
                        alt="PRMSU Logo" 
                        class="mx-auto md:mx-0 w-28 md:w-36 lg:w-44">
                </div>

                <!-- Responsive Typography -->
                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-indigo-900 mb-3 md:mb-5">
                    PRMSU Document Request Portal
                </h1>
                <h2 class="text-lg sm:text-xl md:text-2xl font-medium text-indigo-700 mb-4 md:mb-6">
                    San Marcelino Campus
                </h2>
                <p class="text-base sm:text-lg text-gray-700 mb-6 md:mb-8 max-w-lg mx-auto md:mx-0">
                    Verify Eligibility, Schedule Requests, Receive Notifications and Claim Documents Efficiently.
                </p>

                <!-- Buttons - Stacked on mobile, inline on larger screens -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center md:justify-start">
                    <a 
                        href="{{ route('login') }}" 
                        class="bg-indigo-600 text-white px-6 py-3 sm:px-8 sm:py-3 rounded-lg hover:bg-indigo-700 transition duration-300 font-medium shadow-md text-sm sm:text-base"
                    >
                        Request a Document
                    </a>
                    <a 
                        href="{{ route('login') }}" 
                        class="bg-white border-2 border-indigo-600 text-indigo-600 px-6 py-3 sm:px-8 sm:py-3 rounded-lg hover:bg-indigo-50 transition duration-300 font-medium shadow-sm text-sm sm:text-base"
                    >
                        Track Status
                    </a>
                </div>
            </div>

            <!-- Illustration (Moves to top on mobile due to flex-col-reverse) -->
            <div class="w-full md:w-1/2 flex justify-center" data-aos="fade-left">
                <div class="relative w-full max-w-md lg:max-w-lg">
                    <!-- Subtle gradient background -->
                    <div class="absolute top-0 right-0 w-4/5 h-4/5 bg-gradient-to-br from-blue-100 to-indigo-200 rounded-full opacity-80 -z-10 blur-sm"></div>
                    <img 
                        src="{{ asset('images/document-request-illustration.jpg') }}" 
                        alt="Document Request Process" 
                        class="w-full rounded-lg shadow-lg"
                        loading="lazy">
                </div>
            </div>
        </section>
    </div>
</div>

        <!-- How It Works Section - Enhanced spacing and visual appeal -->
        <section class="mb-20 bg-white p-8 rounded-xl shadow-md">
            <h2 class="text-2xl md:text-3xl font-bold text-center text-indigo-800 mb-10">How It Works</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Step 1 - Enhanced card design -->
                <div class="bg-white p-7 rounded-lg shadow-md border-t-4 border-indigo-500 hover:shadow-lg transition duration-300 flex flex-col h-full">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-center mb-4 text-indigo-700">1. Verify & Submit</h3>
                    <ul class="text-gray-600 space-y-3 flex-grow">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-indigo-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Confirm document requirements</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-indigo-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Complete request form</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-indigo-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Review Summary before submitting</span>
                        </li>
                    </ul>
                </div>

                <!-- Step 2 - Consistent styling -->
                <div class="bg-white p-7 rounded-lg shadow-md border-t-4 border-indigo-500 hover:shadow-lg transition duration-300 flex flex-col h-full">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-center mb-4 text-indigo-700">2. Processing</h3>
                    <ul class="text-gray-600 space-y-3 flex-grow">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-indigo-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Registrar reviews eligibility</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-indigo-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Processing timeframe: 3-7 business days</span>
                        </li>
                    </ul>
                </div>

                <!-- Step 3 -->
                <div class="bg-white p-7 rounded-lg shadow-md border-t-4 border-indigo-500 hover:shadow-lg transition duration-300 flex flex-col h-full">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-center mb-4 text-indigo-700">3. Notification</h3>
                    <ul class="text-gray-600 space-y-3 flex-grow">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-indigo-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Email status updates</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-indigo-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Approval or additional requirements notice</span>
                        </li>
                    </ul>
                </div>

                <!-- Step 4 -->
                <div class="bg-white p-7 rounded-lg shadow-md border-t-4 border-indigo-500 hover:shadow-lg transition duration-300 flex flex-col h-full">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-center mb-4 text-indigo-700">4. Document Release</h3>
                    <ul class="text-gray-600 space-y-3 flex-grow">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-indigo-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Secure pickup at Registrar's Office</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Key Benefits Section - Improved layout and spacing -->
        <section class="mb-16">
            <h2 class="text-2xl md:text-3xl font-bold text-center text-indigo-800 mb-10">Key Benefits</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Benefit 1 - Enhanced card design -->
                <div class="bg-white p-6 rounded-lg shadow-md flex items-start hover:shadow-lg transition duration-300 border-l-4 border-indigo-500">
                    <div class="mr-5 bg-indigo-100 p-3 rounded-full flex-shrink-0">
                        <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-3 text-indigo-700">Refined Processing</h3>
                        <p class="text-gray-700">Automated tracking reduces manual workload and optimize the entire request lifecycle.</p>
                    </div>
                </div>
                
                <!-- Benefit 2 -->
                <div class="bg-white p-6 rounded-lg shadow-md flex items-start hover:shadow-lg transition duration-300 border-l-4 border-indigo-500">
                    <div class="mr-5 bg-indigo-100 p-3 rounded-full flex-shrink-0">
                        <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-3 text-indigo-700">Complete Verification</h3>
                        <p class="text-gray-700">Digital pre-screening minimizes incomplete submissions and ensures all requirements are met.</p>
                    </div>
                </div>
                
                <!-- Benefit 3 -->
                <div class="bg-white p-6 rounded-lg shadow-md flex items-start hover:shadow-lg transition duration-300 border-l-4 border-indigo-500">
                    <div class="mr-5 bg-indigo-100 p-3 rounded-full flex-shrink-0">
                        <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-3 text-indigo-700">Centralized Management</h3>
                        <p class="text-gray-700">All requests managed in one system with complete audit trail for accountability and tracking.</p>
                    </div>
                </div>
                
                <!-- Benefit 4 -->
                <div class="bg-white p-6 rounded-lg shadow-md flex items-start hover:shadow-lg transition duration-300 border-l-4 border-indigo-500">
                    <div class="mr-5 bg-indigo-100 p-3 rounded-full flex-shrink-0">
                        <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-3 text-indigo-700">Reduced Office Traffic</h3>
                        <p class="text-gray-700">Fewer in-person inquiries about status with real-time updates through the portal.</p>
                    </div>
                </div>
                
                <!-- Benefit 5 - Full width card -->
                <div class="bg-white p-6 rounded-lg shadow-md flex items-start hover:shadow-lg transition duration-300 md:col-span-2 border-l-4 border-indigo-500">
                    <div class="mr-5 bg-indigo-100 p-3 rounded-full flex-shrink-0">
                        <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-3 text-indigo-700">Compliance Assurance</h3>
                        <p class="text-gray-700">Standardized process meets institutional policies and ensures consistent handling of all document requests.</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Added Call to Action Section -->
        <section class="text-center bg-indigo-100 p-10 rounded-xl shadow-md" data-aos="fade-up">
            <h2 class="text-2xl md:text-3xl font-bold text-indigo-800 mb-4">Ready to Get Started?</h2>
            <p class="text-lg text-gray-700 mb-6 max-w-2xl mx-auto">Create an account or log in to begin requesting your official documents online.</p>
            <div class="flex justify-center gap-5 flex-wrap">
                <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition duration-300 font-medium shadow-md">Login</a>
                <a href="{{ route('register') }}"  class="bg-white border-2 border-indigo-600 text-indigo-600 px-8 py-3 rounded-lg hover:bg-indigo-50 transition duration-300 font-medium shadow-sm">Register</a>
            </div>
        </section>
    </div>
</div>
@endsection