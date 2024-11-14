<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;

class UserSetupController extends Controller
{
    public function show()
    {
        return view('auth.user-setup');
    }

    public function store(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'username' => 'required|unique:users,username,' . Auth::id() . ',user_id',
                'phone_number' => 'required',
                'address' => 'required',
            ]);

            // Get current user
            $user = User::find(Auth::id());
            
            if (!$user) {
                throw new Exception('User not found');
            }

            // Update user
            $user->username = $validated['username'];
            $user->phone_number = $validated['phone_number'];
            $user->address = $validated['address'];
            $user->save();

            return redirect()->route('dashboard')->with('success', 'Profile setup completed');

        } catch (Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update profile: ' . $e->getMessage()]);
        }
    }
}