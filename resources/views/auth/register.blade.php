<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light dark">

    <title>{{ config('app.name', 'Laravel') }} - Register</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/register.css') }}">

    <!-- Add these CSS styles in register.css or inline -->

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
        /* Update these styles in register.css */

        .register__form {
            width: 50%;
            padding: 48px;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            max-height: 100%;
            /* Add this */
            overflow-y: auto;
            /* Add this */
            scrollbar-width: thin;
            scrollbar-color: rgba(155, 155, 155, 0.5) transparent;
        }

        /* Add scrollbar styling for the form */
        .register__form::-webkit-scrollbar {
            width: 6px;
        }

        .register__form::-webkit-scrollbar-track {
            background: transparent;
        }

        .register__form::-webkit-scrollbar-thumb {
            background-color: rgba(155, 155, 155, 0.5);
            border-radius: 20px;
        }

        /* Update content height */
        .register__content {
            display: flex;
            background-color: var(--form-bg);
            border: 2px solid var(--form-border);
            border-radius: 1rem;
            overflow: hidden;
            width: 100%;
            max-width: 1024px;
            min-height: 600px;
            /* Change from height to min-height */
            backdrop-filter: blur(20px);
        }

        /* Update mobile styles */
        @media screen and (max-width: 768px) {
            .register__content {
                height: auto;
                min-height: auto;
            }

            .register__form {
                max-height: none;
                overflow-y: visible;
            }
        }

        body {
            background-image: url('/assets/images/home/blurbg3.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .overlay {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
    </style>

</head>

<body>
    <div class="container">
        <div class="register__content">
            <form method="POST" action="{{ route('register') }}" class="register__form">
                @csrf

                <div>
                    <h1 class="register__title">
                        <span>Create</span> Account
                    </h1>
                    <p class="register__description">
                        Join our community and start your journey with us.
                    </p>
                </div>

                <div class="register__inputs">
                    <!-- Username -->
                    <div>
                        <label for="username" class="register__label">Username</label>
                        <div class="register__box">
                            <input type="text" id="username" name="username" placeholder="Enter your username"
                                class="register__input" value="{{ old('username') }}" required autocomplete="username">
                            @error('username')
                                <p class="register__error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="register__label">Email</label>
                        <div class="register__box">
                            <input type="email" id="email" name="email" placeholder="Enter your email address"
                                class="register__input" value="{{ old('email') }}" required autocomplete="email">
                            @error('email')
                                <p class="register__error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="register__label">Password</label>
                        <div class="register__box">
                            <input type="password" id="password" name="password" placeholder="Enter your password"
                                class="register__input" required autocomplete="new-password">
                            <i class="ri-eye-off-line register__eye" id="password-toggle"></i>
                            @error('password')
                                <p class="register__error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="register__label">Confirm Password</label>
                        <div class="register__box">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                placeholder="Confirm your password" class="register__input" required
                                autocomplete="new-password">
                            <i class="ri-eye-off-line register__eye" id="confirm-password-toggle"></i>
                        </div>
                    </div>
                </div>

                <div class="mb-4 text-center">
                    <div class="d-inline-block">
                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"
                            data-theme="light"></div>
                        @error('g-recaptcha-response')
                            <p class="register__error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <div class="register__buttons">
                        <button type="submit" class="register__button">Sign Up</button>
                        <a href="{{ route('login') }}" class="register__button register__button-ghost">
                            Login
                        </a>
                    </div>

                    <div class="register__social">
                        <p class="register__social-text">Or sign up with</p>
                        <a href="{{ route('register.google') }}" class="register__social-button">
                            <svg class="register__social-icon" viewBox="0 0 24 24">
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
                            Sign up with Google
                        </a>
                    </div>
                </div>
            </form>

            <img src="{{ asset('assets/images/bg-register.png') }}" alt="Registration illustration"
                class="register__img">
        </div>
    </div>

    <!-- Password Toggle Script -->
    <script>
        function togglePasswordVisibility(inputId, toggleId) {
            const input = document.getElementById(inputId);
            const toggle = document.getElementById(toggleId);

            toggle.addEventListener('click', () => {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);

                toggle.classList.toggle('ri-eye-line');
                toggle.classList.toggle('ri-eye-off-line');
            });
        }

        // Initialize password toggles
        togglePasswordVisibility('password', 'password-toggle');
        togglePasswordVisibility('password_confirmation', 'confirm-password-toggle');
    </script>
</body>

</html>
