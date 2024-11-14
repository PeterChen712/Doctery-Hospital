<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Exception;

class UserSetupController extends Controller
{
    /**
     * Display the user setup form.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('auth.user-setup');
    }

    /**
     * Handle the user setup form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validated = $request->validate([
                'username' => 'required|unique:users,username,' . Auth::id() . ',user_id',
                'phone_number' => 'required',
                'address' => 'required',
            ]);

            // Get the authenticated user
            // $user = Auth::user();
            $user = User::find(Auth::id());


            if (!$user) {
                throw new Exception('User not found.');
            }

            // Update user's profile
            $user->username = $validated['username'];
            $user->phone_number = $validated['phone_number'];
            $user->address = $validated['address'];
            $user->role = 'patient'; // Set the role to 'patient'
            $user->save();

            // Create a new patient record if not exists
            if (!$user->patient) {
                $patient = new Patient();
                $patient->user_id = $user->user_id; // Assuming 'user_id' is the primary key in 'users' table
                // Set other patient attributes if necessary
                $patient->save();
            }

            return redirect()->route('dashboard')->with('success', 'Profile setup completed.');

        } catch (Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update profile: ' . $e->getMessage()]);
        }
    }
}