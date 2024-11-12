<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,appointment_id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:500',
            'is_public' => 'boolean'
        ]);

        $appointment = Appointment::findOrFail($validated['appointment_id']);

        // Ensure the feedback is from the patient who had the appointment
        if ($appointment->patient_id !== Auth::user()->patient->patient_id) {
            return redirect()->back()
                ->with('error', 'Unauthorized to leave feedback for this appointment');
        }

        $feedback = Feedback::create([
            'patient_id' => Auth::user()->patient->patient_id,
            'doctor_id' => $appointment->doctor_id,
            'appointment_id' => $appointment->appointment_id,
            'rating' => $validated['rating'],
            'review' => $validated['review'],
            'is_public' => $validated['is_public'] ?? true
        ]);

        return redirect()->route('patient.appointments.index')
            ->with('success', 'Thank you for your feedback!');
    }

    public function update(Request $request, Feedback $feedback)
    {
        // Check if the feedback belongs to the authenticated patient
        if ($feedback->patient_id !== Auth::user()->patient->patient_id) {
            return redirect()->back()
                ->with('error', 'Unauthorized to update this feedback');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:500',
            'is_public' => 'boolean'
        ]);

        $feedback->update($validated);

        return redirect()->route('patient.appointments.index')
            ->with('success', 'Feedback updated successfully');
    }

    public function doctorResponse(Request $request, Feedback $feedback)
    {
        // Check if the feedback is for the authenticated doctor
        if ($feedback->doctor_id !== Auth::user()->doctor->doctor_id) {
            return redirect()->back()
                ->with('error', 'Unauthorized to respond to this feedback');
        }

        $validated = $request->validate([
            'doctor_response' => 'required|string|max:500'
        ]);

        $feedback->update([
            'doctor_response' => $validated['doctor_response']
        ]);

        return redirect()->back()
            ->with('success', 'Response added successfully');
    }
}