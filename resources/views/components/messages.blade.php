@if(session('message'))
    <div class="fixed bottom-5 right-5 p-4 mb-4 text-sm text-blue-800 border border-blue-300 rounded-lg bg-blue-50 shadow-lg z-50" role="alert">
        <strong>{{ session()->get('message') }}</strong>
    </div>
@endif
