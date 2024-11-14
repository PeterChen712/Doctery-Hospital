<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SocialiteController extends Controller
{
    /**
     * Redirect to the specified provider's authentication page.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function authProviderRedirect($provider)
    {
        if ($provider) {
            return Socialite::driver($provider)->redirect();
        }
        abort(404);
    }

    /**
     * Handle authentication callback from the provider.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function socialAuthentication($provider)
    {
        try {
            if ($provider) {
                $socialUser = Socialite::driver($provider)->user();

                // Check if the user already exists in the database
                $userData = User::where('auth_provider_id', $socialUser->id)->first();

                if (!$userData) {
                    // Create a new user with essential details
                    $userData = User::create([
                        'email' => $socialUser->email,
                        'password' => bcrypt(uniqid()), // Set a random password
                        'auth_provider_id' => $socialUser->id,
                        'auth_provider' => $provider,
                    ]);

                    // Log the user in and redirect to setup page
                    Auth::login($userData);
                    return redirect()->route('user.setup');
                }

                // Update existing user's provider info (in case they log in with a different provider)
                $userData->update([
                    'auth_provider_id' => $socialUser->id,
                    'auth_provider' => $provider,
                ]);

                // Log the user in
                Auth::login($userData);

                // Log field values to troubleshoot why the redirection might not be working
                Log::info('User details after login', [
                    'username' => $userData->username,
                    'phone_number' => $userData->phone_number,
                    'address' => $userData->address,
                ]);

                // Check if setup is needed for missing fields (using empty() to account for empty strings)
                if (empty($userData->username) || empty($userData->phone_number) || empty($userData->address)) {
                    return redirect()->route('user.setup');
                }

                // If all required fields are present, redirect to dashboard
                return redirect()->route('dashboard');
            }
            abort(404);

        } catch (Exception $e) {
            return redirect()->route('login')
                ->withErrors(['error' => 'Authentication failed: ' . $e->getMessage()]);
        }
    }
}
