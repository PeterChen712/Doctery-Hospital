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
        $doctor = Auth::user()->doctor;
        return view('doctor.profile.show', compact('doctor'));
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
            'phone_number' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'license_number' => 'required|string|max:50',
            'education' => 'required|string',
            'experience' => 'required|string',
            'consultation_fee' => 'required|numeric|min:0',
            'password' => ['nullable', 'confirmed', 'min:8'],
            'profile_image' => 'nullable|image|max:2048',
            'cropped_avatar' => 'nullable|string',
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

            // Update doctor specific data
            $doctorData = [
                'specialization' => $validated['specialization'],
                'license_number' => $validated['license_number'],
                'education' => $validated['education'],
                'experience' => $validated['experience'],
                'consultation_fee' => $validated['consultation_fee']
            ];

            $doctor->update($doctorData);

            // Handle password update if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
                $user->save();
            }

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
            Log::error('Doctor profile update failed: ' . $e->getMessage());

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