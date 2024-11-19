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
    public function show()
    {
        $patient = Auth::user()->patient;
        return view('patient.profile.show', compact('patient'));
    }

    public function edit()
    {
        $user = Auth::user();
        $patient = $user->patient;

        return view('patient.profile.edit', compact('user', 'patient'));
    }

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
            'profile_image' => 'nullable|image|max:2048', // For regular file upload
            'cropped_avatar' => 'nullable|string', // For cropped image
        ]);

        try {
            // Start with user data without image
            $userData = [
                'username' => $validated['username'],
                'phone_number' => $validated['phone_number'],
                'address' => $validated['address'],
            ];

            // Handle image upload
            if ($request->filled('cropped_avatar')) {
                // Handle base64 cropped image
                $image_parts = explode(";base64,", $request->cropped_avatar);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);

                $file_name = 'profile_' . time() . '_' . uniqid() . '.' . $image_type;
                $file_path = 'profile_images/' . $file_name;

                // Delete old image if exists
                if ($user->profile_image) {
                    Storage::disk('public')->delete($user->profile_image);
                }

                // Store new image
                if (Storage::disk('public')->put($file_path, $image_base64)) {
                    $userData['profile_image'] = $file_path;
                } else {
                    throw new \Exception('Failed to save image file.');
                }
            } elseif ($request->hasFile('profile_image')) {
                // Handle regular file upload if no cropped image
                if ($user->profile_image) {
                    Storage::disk('public')->delete($user->profile_image);
                }

                $file_path = $request->file('profile_image')->store('profile_images', 'public');
                $userData['profile_image'] = $file_path;
            }

            // Update user data including image path
            $user->update($userData);

            // Update patient data if exists
            if ($patient && isset($validated['date_of_birth'])) {
                $patientData = [
                    'date_of_birth' => $validated['date_of_birth']
                ];

                if (isset($validated['blood_type'])) {
                    $patientData['blood_type'] = $validated['blood_type'];
                }

                $patient->update($patientData);
            }

            // Handle password update if provided
            if ($request->filled('password')) {
                $user->password = bcrypt($validated['password']);
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
        } catch (\Exception $e) {
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
}
