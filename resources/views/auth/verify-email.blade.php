@extends('layouts.default')

@section('content')
<div class="container">
    <h2>Email Verification Required</h2>
    <p>A verification link has been sent to your email address.</p>
    
    @if (session('message'))
        <p class="alert alert-success">{{ session('message') }}</p>
    @endif

    <form method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Resend Verification Email</button>
    </form>
</div>
@endsection
