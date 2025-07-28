<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - VaLibrary</title>
    @vite(['resources/js/app.js'])

</head>
<body>
<div class="login-page-wrapper">
    <div class="login-container">
        <h1 class="login-header">Create Your Account</h1>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <input type="hidden" name="invitation_code" value="{{ $inviteCode }}">

            <div class="form-group">
                <input type="text" name="name" class="form-control" placeholder="Full Name" value="{{ old('name') }}" required autofocus>
                @error('name') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Email Address" value="{{ old('email') }}" required>
                @error('email') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
                @error('password') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
            </div>

            @error('invitation_code') <p class="error-message">{{ $message }}</p> @enderror

            <button type="submit" class="btn-login">Register</button>
        </form>
    </div>
</div>
</body>
</html>
