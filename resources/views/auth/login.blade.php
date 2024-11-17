<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light dark">

    <title>{{ config('app.name', 'Laravel') }} - Login</title>

    <!-- Remixicon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

    <!-- Styles -->

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>
    <div class="container">
        <div class="login__content">
            <img src="assets/images/bg-login.png" alt="login image" class="login__img">

            <form method="POST" action="{{ route('login') }}" class="login__form">
                @csrf

                <div>
                    <h1 class="login__title">
                        <span>Welcome</span> Back
                    </h1>
                    <p class="login__description">
                        Welcome! Please login to continue.
                    </p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="login__status">
                        {{ session('status') }}
                    </div>
                @endif

                <div>
                    <div class="login__inputs">
                        <div>
                            <label for="input-email" class="login__label">{{ __('Email') }}</label>
                            <input type="email" placeholder="Enter your email address" id="input-email" name="email"
                                value="{{ old('email') }}" required autofocus autocomplete="username"
                                class="login__input">
                            @error('email')
                                <p class="login__error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="input-pass" class="login__label">{{ __('Password') }}</label>
                            <div class="login__box">
                                <input type="password" placeholder="Enter your password" id="input-pass" name="password"
                                    required autocomplete="current-password" class="login__input">
                                <i class="ri-eye-off-line login__eye" id="input-icon"></i>
                                @error('password')
                                    <p class="login__error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="login__check">
                        <input type="checkbox" class="login__check-input" id="remember_me" name="remember">
                        <label for="remember_me" class="login__check-label">
                            {{ __('Remember me') }}
                        </label>
                    </div>
                </div>

                <div>
                    <div class="login__buttons">
                        <button type="submit" class="login__button">{{ __('Log in') }}</button>
                        <a href="{{ route('register') }}" class="login__button login__button-ghost">
                            {{ __('Sign Up') }}
                        </a>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="login__forgot">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <div class="login__social">
                        <a href="{{ route('login.google') }}" class="login__social-button"
                            title="Login with Google">
                            <svg class="login__social-icon" viewBox="0 0 24 24">
                                <path
                                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                    fill="#4285F4" />
                                <path
                                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                    fill="#34A853" />
                                <path
                                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                    fill="#FBBC05" />
                                <path
                                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                    fill="#EA4335" />
                            </svg>
                            Sign in with Google
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Password Toggle Script -->
    <script>
        const showHiddenInput = (inputPass, inputIcon) => {
            const input = document.getElementById(inputPass),
                iconEye = document.getElementById(inputIcon)

            iconEye.addEventListener('click', () => {
                if (input.type === 'password') {
                    input.type = 'text'
                    iconEye.classList.add('ri-eye-line')
                    iconEye.classList.remove('ri-eye-off-line')
                } else {
                    input.type = 'password'
                    iconEye.classList.remove('ri-eye-line')
                    iconEye.classList.add('ri-eye-off-line')
                }
            })
        }

        showHiddenInput('input-pass', 'input-icon')
    </script>
</body>

</html>
