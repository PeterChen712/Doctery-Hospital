<?php
// app/Http/Controllers/Doctor/FeedbackResponseController.php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackResponseController extends Controller
{
    public function store(Request $request, Feedback $feedback)
    {
        // Validate ownership
        if ($feedback->doctor_id !== Auth::user()->doctor->doctor_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'doctor_response' => 'required|string|min:10'
        ]);

        try {
            $feedback->update([
                'doctor_response' => $validated['doctor_response'],
                'status' => 'APPROVED'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Response submitted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error submitting response'
            ], 500);
        }
    }
}