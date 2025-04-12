{{-- resources\views\layouts\default.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'PRMSU Document Request Portal San Marcellino' }}</title>

    @if (app()->environment('production'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <link rel="icon" href="{{ asset('images/PRMSU.png') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.min.css">
    
    @stack('styles')
    <style>
        @keyframes fadeIn {
          from { opacity: 0; transform: translateY(10px); }
          to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
          animation: fadeIn 0.5s ease-out;
        }
      </style>
</head>

<body class="bg-gray-100 text-gray-800 bg-cover bg-center md:bg-fixed m-0 flex flex-col min-h-screen overflow-x-hidden">
    <x-nav/>
    @includeIf('components.messages')
    
    <!-- Main Content Section -->
    <main class="flex-grow mx-auto p-4 sm:p-6 @yield('main-class') mt-20 w-full max-w-screen-2xl">
        @yield('content')
    </main> 

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-4 shadow-inner">
        <div class="container mx-auto text-center">
            <p>&copy; {{ date('Y') }} PRMSU Document Request Portal | All Rights Reserved</p>
        </div>
    </footer>

    <script>
    // Improved Logout Timer
    (function() {
        const INACTIVE_TIMEOUT = 1440 * 60 * 1000; 
        let logoutTimer;

        function resetLogoutTimer() {
            clearTimeout(logoutTimer);
            logoutTimer = setTimeout(forceLogout, INACTIVE_TIMEOUT);
        }

        function forceLogout() {
            Swal.fire({
                title: 'Session Expired',
                text: 'You have been logged out due to inactivity.',
                icon: 'warning',
                confirmButtonText: 'OK'
            }).then(() => {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('logout') }}";

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = "{{ csrf_token() }}";
                form.appendChild(csrfToken);

                document.body.appendChild(form);
                form.submit();
            });
        }

        // Event listeners with safe checking
        ['mousemove', 'keypress', 'click', 'scroll'].forEach(event => {
            document.addEventListener(event, resetLogoutTimer);
        });

        // Sidebar Toggle - Safe Implementation
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.querySelector('.toggle-sidebar-btn');
            const sidebar = document.querySelector('.sidebar');

            // Safe check to prevent null reference errors
            if (toggleBtn && sidebar) {
                function isMobile() {
                    return window.innerWidth <= 768;
                }

                function toggleSidebar() {
                    if (isMobile()) {
                        sidebar.classList.toggle('active');
                    } else {
                        sidebar.classList.toggle('collapsed');
                    }
                }

                toggleBtn.addEventListener('click', (event) => {
                    toggleSidebar();
                    event.stopPropagation();
                });

                // Mobile sidebar close logic
                document.addEventListener('click', (event) => {
                    if (isMobile() && 
                        !sidebar.contains(event.target) && 
                        !toggleBtn.contains(event.target)) {
                        sidebar.classList.remove('active');
                    }
                });
            }
        });

        // Initial timer start
        resetLogoutTimer();
    })();
    </script>
    @stack('scripts')
</body>
</html>