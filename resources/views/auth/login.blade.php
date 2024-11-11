@extends('theme.auth-layout')
@section('content')
    <div class="card p-4">
        <h2 class="text-center mb-4">Login</h2>
        <form id="loginForm" method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Check for error message in session -->
            @if(session('error'))
                <div id="error-message" class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                    <span id="error-text">{{ session('error') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @else
                <div id="error-message" class="alert alert-danger alert-dismissible fade show mb-3" role="alert" style="display: none;">
                    <span id="error-text"></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input class="form-control" id="email" name="email" required autofocus>
            </div>
        
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
        
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <p class="text-center mt-4">Don’t have an account? <a href="{{ route('register') }}">Register</a></p>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function (event) {
            event.preventDefault();

            const errorMessage = document.getElementById('error-message');
            const errorText = document.getElementById('error-text');
            errorMessage.style.display = 'none';

            // Get values from the form fields
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();

            // Check if the email and password fields are filled out
            if (!email) {
                errorText.innerText = 'Please enter your email.';
                errorMessage.style.display = 'block';
                return;
            }
            if (!password) {
                errorText.innerText = 'Please enter your password.';
                errorMessage.style.display = 'block';
                return;
            }

            // Validate the email format
            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!emailPattern.test(email)) {
                errorText.innerText = 'Please enter a valid email address.';
                errorMessage.style.display = 'block';
                return;
            }

            // If validation passes, submit the form
            this.submit();
        });
    </script>
@endsection
