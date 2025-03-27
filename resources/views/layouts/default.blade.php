<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'File Retrieval System' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('images/PRMSU.png') }}">
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-100 text-gray-800 bg-cover bg-center md:bg-fixed m-0 flex flex-col min-h-screen" 
      style="background-image: url('{{ asset('images/bg-login-register.jpg') }}');">

    <x-nav/>
    @includeIf('components.messages')
    
    <!-- Main Content Section -->
    <main class="flex-grow max-w-screen-lg mx-auto p-6 @yield('main-class') mt-20">
        @yield('content')
    </main> 

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-4 shadow-inner">
        <div class="container mx-auto text-center">
            <p>&copy; {{ date('Y') }} PRMSU File Retrieval System | All Rights Reserved</p>
        </div>
    </footer>

    <script>
    // Improved Logout Timer
    (function() {
        const INACTIVE_TIMEOUT = 30 * 60 * 1000; // 30 minutes
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
</body>
</html>