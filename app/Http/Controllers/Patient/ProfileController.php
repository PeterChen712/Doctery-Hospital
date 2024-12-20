<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Exception;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show()
    {
        $patient = Auth::user()->patient;
        return view('patient.profile.show', compact('patient'));
    }

    /**
     * Show the form for editing the profile.
     */
    public function edit()
    {
        $user = Auth::user();
        $patient = $user->patient;

        return view('patient.profile.edit', compact('user', 'patient'));
    }

    /**
     * Create profile view
     */
    public function create()
    {
        $user = Auth::user();
        return view('patient.profile.create', compact('user'));
    }

    /**
     * Store new profile
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $patient = $user->patient;

        $validated = $request->validate([
            'date_of_birth' => 'required|date|before:today',
            'blood_type' => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'allergies' => 'nullable|string|max:500',
            'medical_history' => 'nullable|string|max:1000',
            'emergency_contact' => 'required|string|max:255',
        ]);

        try {
            $user->patient()->create($validated);

            return redirect()
                ->route('patient.dashboard')
                ->with('success', 'Profile created successfully');
        } catch (Exception $e) {
            Log::error('Profile creation failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create profile: ' . $e->getMessage()]);
        }
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $patient = $user->patient;

        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'phone_number' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'password' => ['nullable', 'confirmed', 'min:8'],
            'profile_image' => 'nullable|image|max:2048',
            'cropped_image' => 'nullable|string',
            'allergies' => 'nullable|string|max:500',
            'medical_history' => 'nullable|string|max:1000',
            'emergency_contact' => 'nullable|string|max:255',
        ]);

        try {
            // Handle cropped image upload
            if ($request->filled('cropped_image')) {
                // Delete old image if exists
                if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                    Storage::disk('public')->delete($user->profile_image);
                }

                // Clean up base64 image data
                $base64_image = $request->cropped_image;
                if (strpos($base64_image, ';base64,') !== false) {
                    list($type, $base64_image) = explode(';', $base64_image);
                    list(, $base64_image) = explode(',', $base64_image);
                }

                // Decode and save image
                $image_data = base64_decode($base64_image);
                $file_name = 'profile_' . time() . '_' . uniqid() . '.jpg';
                $file_path = 'profile_images/' . $file_name;

                Storage::disk('public')->makeDirectory('profile_images', 0755, true, true);

                if (!Storage::disk('public')->put($file_path, $image_data)) {
                    throw new Exception('Failed to save image file.');
                }

                $validated['profile_image'] = $file_path;
            }

            // Update user data
            $userData = [
                'username' => $validated['username'],
                'phone_number' => $validated['phone_number'],
                'address' => $validated['address'],
            ];

            if (isset($validated['profile_image'])) {
                $userData['profile_image'] = $validated['profile_image'];
            }

            $user->update($userData);

            // Update patient data
            if ($patient) {
                $patientData = array_filter([
                    'date_of_birth' => $validated['date_of_birth'] ?? null,
                    'blood_type' => $validated['blood_type'] ?? null,
                    'allergies' => $validated['allergies'] ?? null,
                    'medical_history' => $validated['medical_history'] ?? null,
                    'emergency_contact' => $validated['emergency_contact'] ?? null,
                ]);

                if (!empty($patientData)) {
                    $patient->update($patientData);
                }
            }

            // Update password if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
                $user->save();
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile updated successfully',
                    'redirect' => route('patient.profile.show')
                ]);
            }

            return redirect()
                ->route('patient.profile.show')
                ->with('success', 'Profile updated successfully');

        } catch (Exception $e) {
            Log::error('Profile update failed: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update profile: ' . $e->getMessage()
                ], 422);
            }

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update profile: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete the user's profile photo.
     */
    public function deletePhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $user->update(['profile_image' => null]);

        return back()->with('success', 'Profile photo removed successfully');
    }
}