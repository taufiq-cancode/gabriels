@extends('theme.auth-layout')
@section('content')
    <div class="card p-4">
        <h2 class="text-center mb-4">Register</h2>
        <form id="registerForm" method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Display server-side error messages -->
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
        
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="firstname" class="form-label">First Name</label>
                    <input type="text" class="form-control @error('firstname') is-invalid @enderror" id="firstname" name="firstname" value="{{ old('firstname') }}" required>
                    @error('firstname')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="lastname" class="form-label">Last Name</label>
                    <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname" name="lastname" value="{{ old('lastname') }}" required>
                    @error('lastname')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" required>
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        
            <button type="submit" class="btn btn-primary w-100">Register</button>
            <p class="text-center mt-4">Already have an account? <a href="{{ route('login') }}">Login</a></p>
        </form>
    </div>

        <script>
            document.getElementById('registerForm').addEventListener('submit', function (event) {
                event.preventDefault();

                const errorMessage = document.getElementById('error-message');
                const errorText = document.getElementById('error-text');
                errorMessage.style.display = 'none';

                // Clear error message text at the beginning of each validation cycle
                errorText.innerText = '';

                // Get values from form fields
                const firstname = document.getElementById('firstname').value.trim();
                const lastname = document.getElementById('lastname').value.trim();
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value;
                const passwordConfirmation = document.getElementById('password_confirmation').value;

                // Basic validations with logging
                if (!firstname) {
                    errorText.innerText = 'Please enter your first name.';
                    errorMessage.style.display = 'block';
                    return;
                }
                if (!lastname) {
                    errorText.innerText = 'Please enter your last name.';
                    errorMessage.style.display = 'block';
                    return;
                }
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
                if (!passwordConfirmation) {
                    errorText.innerText = 'Please confirm your password.';
                    errorMessage.style.display = 'block';
                    return;
                }

                // Email format validation
                const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                if (!emailPattern.test(email)) {
                    errorText.innerText = 'Please enter a valid email address.';
                    errorMessage.style.display = 'block';
                    return;
                }

                // Password match validation
                if (password !== passwordConfirmation) {
                    errorText.innerText = 'Passwords do not match.';
                    errorMessage.style.display = 'block';
                    return;
                }

                // Reduced password validation (minimum 8 characters and at least 1 number)
                const simplePasswordPattern = /^(?=.*\d)[A-Za-z\d]{8,}$/;
                if (!simplePasswordPattern.test(password)) {
                    errorText.innerText = 'Password must be at least 8 characters long and contain at least one number.';
                    errorMessage.style.display = 'block';
                    return;
                }

                // Submit the form if all validations pass
                try {
                    // Submit the form via JavaScript to avoid any traditional form submission and page reload
                    event.target.submit();
                } catch (error) {
                    console.error('Form submission error:', error);
                    errorText.innerText = 'An error occurred while submitting the form. Please try again.';
                    errorMessage.style.display = 'block';
                }
            });
        </script>
@endsection
