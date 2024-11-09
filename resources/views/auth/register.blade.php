@extends('theme.auth-layout')
@section('content')
    <div class="card p-4">
        <h2 class="text-center mb-4">Register</h2>
        <form id="registerForm" method="POST" action="{{ route('register') }}">
            @csrf

            <div id="error-message" class="text-danger mb-3" style="display: none;"></div>
        
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="firstname" class="form-label">First Name</label>
                    <input type="text" class="form-control" name="firstname" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="lastname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="lastname" required>
                </div>
            </div>
        
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
        
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
        
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="password_confirmation" required>
            </div>
        
            <button type="submit" class="btn btn-primary w-100">Register</button>
            <p class="text-center mt-4">Already have an account? <a href="{{ route('login') }}">Login</a></p>
        </form>
    </div>
@endsection