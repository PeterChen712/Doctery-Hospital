<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompleteProfileController extends Controller
{
    public function create()
    {
        return view('patient.profile.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date_of_birth' => 'required|date|before:today',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'blood_type' => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
        ]);

        $patient = Patient::create([
            'user_id' => Auth::id(),
            'date_of_birth' => $validated['date_of_birth'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'blood_type' => $validated['blood_type'],
        ]);

        return redirect()
            ->route('patient.dashboard')
            ->with('success', 'Profile created successfully');
    }
}
