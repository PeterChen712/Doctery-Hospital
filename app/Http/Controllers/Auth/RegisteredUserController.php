<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Exception;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'g-recaptcha-response' => ['required', 'recaptcha'],
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('user.setup');
    }

    /**
     * Redirect the user to Google authentication page.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function redirectToGoogleRegister(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();

    }

    /**
     * Handle Google callback and user registration/login.
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user exists
            $existingUser = User::where('email', $googleUser->email)->first();
            
            if ($existingUser) {
                // Update existing user
                $existingUser->update([
                    'auth_provider' => 'google',
                    'auth_provider_id' => $googleUser->id,
                    'email_verified_at' => now(),
                ]);
                
                Auth::login($existingUser);
                
                return redirect()->intended(RouteServiceProvider::HOME);
            }
            
            // Create new user
            $user = User::create([
                'email' => $googleUser->email,
                'username' => explode('@', $googleUser->email)[0] . '_' . Str::random(5),
                'password' => Hash::make(Str::random(24)),
                'auth_provider' => 'google',
                'auth_provider_id' => $googleUser->id,
                'email_verified_at' => now(),
            ]);

            Auth::login($user);

            // Redirect to setup if needed
            if (!$user->phone_number || !$user->address) {
                return redirect()->route('user.setup');
            }

            return redirect()->intended(RouteServiceProvider::HOME);

        } catch (Exception $e) {
            return redirect()->route('login')
                ->withErrors(['error' => 'Google authentication failed: ' . $e->getMessage()]);
        }
    }

    public function handleGoogleRegisterCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user already exists
            $existingUser = User::where('email', $googleUser->email)->first();

            if ($existingUser) {
                // Inform the user that the account exists
                return redirect()->route('login')->withErrors([
                    'email' => 'An account with this email already exists. Please login.',
                ]);
            }

            // Create new user
            $user = User::create([
                'username' => $this->generateUsername($googleUser->email),
                'email' => $googleUser->email,
                'password' => Hash::make(Str::random(24)),
                'auth_provider' => 'google',
                'auth_provider_id' => $googleUser->id,
                'email_verified_at' => now(),
            ]);

            event(new Registered($user));

            Auth::login($user);

            // Redirect to user setup or home
            return redirect()->route('user.setup');

        } catch (Exception $e) {
            return redirect()->route('register')
                ->withErrors(['error' => 'Google registration failed: ' . $e->getMessage()]);
        }
    }

    private function generateUsername(string $email): string
    {
        $baseUsername = explode('@', $email)[0];
        $username = $baseUsername;
        $counter = 1;

        // Ensure the username is unique
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }
}