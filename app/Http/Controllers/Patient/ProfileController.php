<?php
// app/Http/Controllers/Patient/ProfileController.php
namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $patient = Auth::user()->patient;
        return view('patient.profile.show', compact('patient'));
    }

    public function edit()
    {
        $patient = Auth::user()->patient;
        return view('patient.profile.edit', compact('patient'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'date_of_birth' => 'required|date',
            'phone' => 'required|string',
            'address' => 'required|string',
            'blood_type' => 'required|string'
        ]);

        Auth::user()->patient->update($validated);
        Auth::user()->patient->update([
            'phone_number' => $validated['phone'],
            'address' => $validated['address']
        ]);

        return redirect()->route('patient.profile.show')->with('success', 'Profile updated successfully');
    }
}