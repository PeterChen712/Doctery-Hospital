<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $doctor = $user->doctor;
        return view('doctor.profile.show', compact('user', 'doctor'));
    }

    public function edit()
    {
        $user = Auth::user();
        $doctor = $user->doctor;
        return view('doctor.profile.edit', compact('user', 'doctor'));
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $doctor = $user->doctor;

        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'license_number' => 'required|string|max:50',
            'profile_image' => 'nullable|image|max:2048',
            'cropped_image' => 'nullable|string'
        ]);

        try {
            // Handle cropped image upload
            if ($request->filled('cropped_image')) {
                if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                    Storage::disk('public')->delete($user->profile_image);
                }

                $image_parts = explode(";base64,", $request->cropped_image);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);

                $file_name = 'profile_' . time() . '_' . uniqid() . '.' . $image_type;
                $file_path = 'profile_images/' . $file_name;

                Storage::disk('public')->makeDirectory('profile_images', 0755, true, true);

                if (!Storage::disk('public')->put($file_path, $image_base64)) {
                    throw new \Exception('Failed to save image file.');
                }

                $validated['profile_image'] = $file_path;
            }

            // Update user data
            $userData = [
                'username' => $validated['username'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
                'address' => $validated['address'],
            ];

            if (isset($validated['profile_image'])) {
                $userData['profile_image'] = $validated['profile_image'];
            }

            $user->update($userData);

            // Update doctor data
            $doctor->update([
                'specialization' => $validated['specialization'],
                'license_number' => $validated['license_number']
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile updated successfully',
                    'redirect' => route('doctor.profile.show')
                ]);
            }

            return redirect()
                ->route('doctor.profile.show')
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
