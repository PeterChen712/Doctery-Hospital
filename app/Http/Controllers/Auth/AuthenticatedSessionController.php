<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Exception;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function authenticated()
    {
        if (Auth::user()->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->role == 'doctor') {
            return redirect()->route('doctor.dashboard');
        } elseif (Auth::user()->role == 'patient') {
            return redirect()->route('patient.dashboard');
        }
        
        return redirect(RouteServiceProvider::HOME);
    }

    public function redirectToGoogleLogin(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google callback for login.
     */
    public function handleGoogleLoginCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user exists
            $existingUser = User::where('email', $googleUser->email)->first();

            if ($existingUser) {
                Auth::login($existingUser);

                return redirect()->intended(RouteServiceProvider::HOME);
            } else {
                // Inform the user that no account exists
                return redirect()->route('register')->withErrors([
                    'email' => 'No account found with this email. Please sign up.',
                ]);
            }

        } catch (Exception $e) {
            return redirect()->route('login')
                ->withErrors(['error' => 'Google login failed: ' . $e->getMessage()]);
        }
    }
}
