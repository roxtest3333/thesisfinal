@if (session()->has('message'))
<script>
    localStorage.setItem('flash_message', '{{ session('message') }}');
</script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const message = localStorage.getItem('flash_message');
        if (message) {
            alert(message); // Temporary to check if message persists
            localStorage.removeItem('flash_message'); // Clear after showing
        }
    });
</script>
