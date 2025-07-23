<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - VaLibrary</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/js/app.js'])
</head>
<body class="login-page">

<div class="login-container">
    <h1 class="login-header">VaLibrary Login</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf <!-- CSRF Protection -->

        <div class="form-group">
            <label for="email" class="visually-hidden">Email</label>
            <input type="email"
                   name="email"
                   id="email"
                   class="form-control"
                   placeholder="Email Address"
                   value="{{ old('email') }}"
                   required
                   autofocus>

            @error('email')
            <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="visually-hidden">Password</label>
            <input type="password"
                   name="password"
                   id="password"
                   class="form-control"
                   placeholder="Password"
                   required>
        </div>

        <button type="submit" class="btn">
            Log In
        </button>
    </form>
</div>

</body>
</html>
