<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div x-data="{ open: false }" class="bg-gray-800 fixed w-full z-20 top-0 left-0 shadow-lg">
    <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-6">
        <div class="relative flex items-center justify-between h-16">
            <!-- Logo and Title -->
            <div class="flex items-center">
                <a href="@auth('web') {{ route('admin.dashboard') }}
                    @elseif(auth('student')->check()) {{ route('student.dashboard') }}
                    @endauth"
                    class="flex items-center hover:text-sky-400 transition-transform transform hover:scale-105">
                    <img src="{{ asset('images/PRMSU.png') }}" alt="Logo" class="h-8 mr-2 hidden md:block">
                    <span class="text-lg font-semibold hidden md:block">PRMSU San Marcelino Document Request Portal</span>
                    <span class="text-lg font-semibold block md:hidden">PRMSU-SM File Portal</span>
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="absolute inset-y-0 right-0 flex items-center md:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px" fill="#e8eaed">
                        <path d="M3 6h18M3 12h18m-18 6h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            <!-- Desktop Menu Items -->
            <div class="hidden md:block md:ml-6">
                <div class="flex space-x-1">
                    @guest        
                        <a href="{{ route('login') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                            Register
                        </a>
                        <a href="{{ route('student.password.request') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                            Reset Password
                        </a>
                    @endguest
            
                    @auth('web')
                        @if(auth()->check() && auth()->user()->role === 'superadmin')
                            <a href="{{ route('admin.register') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                                Admins
                            </a>
                        @endif
                        <a href="{{ route('admin.schedules.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                            Requests
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                            Reports
                        </a>
                        <a href="{{ route('admin.students.index')}}" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                            Students
                        </a>
                        <a href="{{ route('admin.files.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                            Files
                        </a>
                        <a href="{{ route('admin.file-requirements.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                            File Requirements
                        </a>
                        <a href="{{ route('admin.school-years-semesters.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                            Terms
                        </a>
                    @endauth
            
                    @auth('student')
                        <a href="{{ route('student.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                            Dashboard
                        </a>
                        <a href="{{ route('student.file_requests.create') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                            New Request
                        </a>
                        <a href="{{ route('student.history') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                            History
                        </a>
                        <a href="{{ route('student.profile.show') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                            My Profile
                        </a>
                    @endauth
            
                    @if(Auth::guard('web')->check() || Auth::guard('student')->check())
                        <form action="{{ route('logout') }}" method="POST" class="flex">
                            @csrf
                            <button type="submit" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                                Logout
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="open" @click.away="open = false" class="md:hidden bg-gray-800 pb-3 px-2">
        <div class="px-2 pt-2 pb-3 space-y-1">
            @guest        
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                    Login
                </a>
                <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                    Register
                </a>
                <a href="{{ route('student.password.request') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                    Reset Password
                </a>
            @endguest
    
            @auth('web')
                @if(auth()->check() && auth()->user()->role === 'superadmin')
                    <a href="{{ route('admin.register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                        Admins
                    </a>
                @endif
                <a href="{{ route('admin.schedules.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                    Requests
                </a>
                <a href="{{ route('admin.reports.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                    Reports
                </a>
                <a href="{{ route('admin.students.index')}}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                    Students
                </a>
                <a href="{{ route('admin.files.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                    Files
                </a>
                <a href="{{ route('admin.file-requirements.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                    File Requirements
                </a>
                <a href="{{ route('admin.school-years-semesters.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                    Terms
                </a>
            @endauth
    
            @auth('student')
                <a href="{{ route('student.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                    Dashboard
                </a>
                <a href="{{ route('student.file_requests.create') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                    New Request
                </a>
                <a href="{{ route('student.history') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                    History
                </a>
                <a href="{{ route('student.profile.show') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                    My Profile
                </a>
            @endauth
    
            @if(Auth::guard('web')->check() || Auth::guard('student')->check())
                <form action="{{ route('logout') }}" method="POST" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700 hover:text-sky-400">
                        Logout
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let currentUrl = window.location.href;

        // Apply active state to all nav links
        document.querySelectorAll("#navbar-main a").forEach(link => {
            if (link.href === currentUrl) {
                link.classList.add("bg-gray-700", "text-sky-400");
            }
        });
    });
</script>