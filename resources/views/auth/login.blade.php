@extends('theme.auth-layout')
@section('content')
    <div class="card p-4">
        <h2 class="text-center mb-4">Login</h2>
        <form id="loginForm" method="POST" action="{{ route('login') }}">
            @csrf
        
            <div id="error-message" class="text-danger mb-3" style="display: none;"></div>
        
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required autofocus>
            </div>
        
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
        
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <p class="text-center mt-4">Don’t have an account? <a href="{{ route('register') }}">Register</a></p>
    </div>
@endsection
