<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $additionalData = null;

        // Load role-specific data
        if ($user->hasRole('doctor')) {
            $additionalData = Doctor::where('user_id', $user->user_id)->first();
        } elseif ($user->hasRole('patient')) {
            $additionalData = Patient::where('user_id', $user->user_id)->first();
        }

        return view('profile.edit', [
            'user' => $user,
            'additionalData' => $additionalData
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Validate basic user data
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->user_id, 'user_id')],
            'phone_number' => ['required', 'string', 'max:15'],
            'address' => ['required', 'string', 'max:255'],
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $this->handleProfileImageUpload($request);
        }

        // Update user basic information
        $user->fill($validated);
        $user->save();

        // Update role-specific information
        if ($user->hasRole('doctor')) {
            $this->updateDoctorProfile($request, $user);
        } elseif ($user->hasRole('patient')) {
            $this->updatePatientProfile($request, $user);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // Delete profile image if exists
        if ($user->profile_image) {
            Storage::delete('public/profile-images/' . $user->profile_image);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Handle profile image upload
     */
    private function handleProfileImageUpload(Request $request): string
    {
        $file = $request->file('profile_image');
        $filename = time() . '_' . $file->getClientOriginalName();
        
        // Delete old image if exists
        if ($request->user()->profile_image) {
            Storage::delete('public/profile-images/' . $request->user()->profile_image);
        }

        // Store new image
        $file->storeAs('public/profile-images', $filename);
        
        return $filename;
    }

    /**
     * Update doctor specific profile information
     */
    private function updateDoctorProfile(Request $request, $user): void
    {
        $validated = $request->validate([
            'specialization' => ['required', 'string', 'max:255'],
            'license_number' => ['required', 'string', 'max:50'],
            'education' => ['required', 'string'],
            'experience' => ['required', 'string'],
            'consultation_fee' => ['required', 'numeric', 'min:0'],
        ]);

        Doctor::where('user_id', $user->user_id)->update($validated);
    }

    /**
     * Update patient specific profile information
     */
    private function updatePatientProfile(Request $request, $user): void
    {
        $validated = $request->validate([
            'date_of_birth' => ['required', 'date'],
            'blood_type' => ['required', 'string', 'max:5'],
            'allergies' => ['nullable', 'string'],
            'medical_history' => ['nullable', 'string'],
            'emergency_contact' => ['required', 'string', 'max:255'],
        ]);

        Patient::where('user_id', $user->user_id)->update($validated);
    }
}