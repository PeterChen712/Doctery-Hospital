<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $admin = Auth::user(); // Get authenticated user
        return view('admin.profile.edit', ['user' => $admin]); // Pass as 'user'
    }

    public function update(Request $request)
    {
        $admin = $request->user();

        
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            // Handle file upload
            $path = $request->file('profile_image')->store('profile-images', 'public');
            $validated['profile_image'] = $path;
        }

        $admin->update($validated);

        return redirect()
            ->route('admin.profile.edit')
            ->with('success', 'Profile updated successfully');
    }
}