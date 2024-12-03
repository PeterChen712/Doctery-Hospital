<?php
// app/Http/Controllers/Patient/FeedbackController.php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{

    public function hasFeedback($recordId)
    {
        try {
            $medicalRecord = MedicalRecord::findOrFail($recordId);

            // Check if user owns this record
            if ($medicalRecord->patient_id !== Auth::user()->patient->patient_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $hasFeedback = $medicalRecord->feedback()->exists();

            return response()->json([
                'success' => true,
                'hasFeedback' => $hasFeedback
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking feedback: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error checking feedback status'
            ], 500);
        }
    }

    // Update your FeedbackController store method:

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate request
            $validated = $request->validate([
                'medical_record_id' => 'required|exists:medical_records,record_id',
                'overall_rating' => 'required|integer|min:1|max:5',
                'review' => 'required|string|max:500',
            ]);

            // Create feedback
            $feedback = Feedback::create([
                'medical_record_id' => $validated['medical_record_id'],
                'patient_id' => Auth::user()->patient->patient_id,
                'overall_rating' => $validated['overall_rating'],
                'review' => $validated['review'],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Feedback submitted successfully!',
                'feedback' => $feedback
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit feedback: ' . $e->getMessage()
            ], 500);
        }
    }
}
