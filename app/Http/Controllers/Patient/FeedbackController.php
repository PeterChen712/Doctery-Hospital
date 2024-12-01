<?php
// app/Http/Controllers/Patient/FeedbackController.php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FeedbackController extends Controller
{

    
// Update your FeedbackController store method:
    public function store(Request $request, int $recordId)
    {
        try {
            // Find medical record first
            $medicalRecord = MedicalRecord::findOrFail($recordId);

            // Check if user owns this record
            if ($medicalRecord->patient_id !== Auth::user()->patient->patient_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            // Validate input
            $validated = $request->validate([
                'overall_rating' => 'required|integer|between:1,5',
                'category' => 'required|string|in:GENERAL,DOCTOR_SERVICE,FACILITY,STAFF_SERVICE,WAIT_TIME,TREATMENT_QUALITY,COMMUNICATION',
                'review' => 'required|string|min:10',
            ]);

            // Create feedback with explicit record_id
            $feedback = new Feedback([
                'overall_rating' => $validated['overall_rating'],
                'category' => $validated['category'],
                'review' => $validated['review'],
                'status' => 'PENDING'
            ]);

            // Set relationships explicitly
            $feedback->record_id = $medicalRecord->record_id;
            $feedback->patient_id = Auth::user()->patient->patient_id;
            $feedback->doctor_id = $medicalRecord->doctor_id;

            $feedback->save();

            return response()->json([
                'success' => true,
                'message' => 'Feedback submitted successfully',
                'feedback' => $feedback
            ]);

        } catch (\Exception $e) {
            Log::error('Feedback submission error: ' . $e->getMessage(), [
                'record_id' => $recordId,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error submitting feedback',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
