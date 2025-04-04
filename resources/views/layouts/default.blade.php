<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ $title ?? 'File Retrieval System' }}</title>
    
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    
        <link rel="icon" href="{{ asset('images/PRMSU.png') }}">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js" defer></script>
        
        <!-- Add Flatpickr resources here -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
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
        (function() {
            const INACTIVE_TIMEOUT = 950 * 60 * 1000; // 30 minutes
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
    
            // Event listeners to reset inactivity timer
            ['mousemove', 'keypress', 'click', 'scroll'].forEach(event => {
                document.addEventListener(event, resetLogoutTimer);
            });
    
            // Session Keep-Alive Request Every 5 Minutes
            setInterval(() => {
                fetch('/keep-alive', {
                    method: 'POST',
                    headers: { 
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                    }
                });
            }, 5 * 60 * 1000); // Every 5 minutes
    
            // Sidebar Toggle - Safe Implementation
            document.addEventListener('DOMContentLoaded', () => {
                const toggleBtn = document.querySelector('.toggle-sidebar-btn');
                const sidebar = document.querySelector('.sidebar');
    
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
    
            // Start the inactivity timer
            resetLogoutTimer();
        })();
    </script>
    
</body>
</html>