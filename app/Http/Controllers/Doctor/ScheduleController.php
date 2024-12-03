<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Crypt;

class ScheduleController extends Controller
{
    use AuthorizesRequests;

    private function getAvailableSchedules($doctorId, $month, $year)
    {
        return Schedule::where('doctor_id', $doctorId)
            ->whereYear('schedule_date', $year)
            ->whereMonth('schedule_date', $month)
            ->where('is_available', true)
            ->orderBy('schedule_date')
            ->orderBy('start_time')
            ->get();
    }

    public function index(Request $request)
    {
        try {
            $doctor = Auth::user()->doctor;

            if (!$doctor) {
                return redirect()->back()->withErrors('You are not associated with any doctor profile.');
            }

            $month = $request->input('month', Carbon::now()->month);
            $year = $request->input('year', Carbon::now()->year);

            // Get schedules
            $schedules = $this->getAvailableSchedules($doctor->doctor_id, $month, $year);

            // Group schedules by date
            $schedulesByDate = $schedules->groupBy(function ($schedule) {
                return Carbon::parse($schedule->schedule_date)->format('Y-m-d');
            });

            // Calculate calendar data
            $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
            $firstDayOfMonth = Carbon::createFromDate($year, $month, 1)->dayOfWeek;

            // Navigation dates
            $currentDate = Carbon::createFromDate($year, $month, 1);
            $date = $currentDate->format('Y-m-d');
            $prevMonthDate = $currentDate->copy()->subMonth();
            $nextMonthDate = $currentDate->copy()->addMonth();

            return view('doctor.schedules.index', [
                'year' => $year,
                'month' => $month,
                'schedulesByDate' => $schedulesByDate,
                'prevMonth' => $prevMonthDate->month,
                'prevYear' => $prevMonthDate->year,
                'nextMonth' => $nextMonthDate->month,
                'nextYear' => $nextMonthDate->year,
                'daysInMonth' => $daysInMonth,
                'firstDayOfMonth' => $firstDayOfMonth,
                'date' => $date
            ]);
        } catch (\Exception $e) {
            Log::error('Schedule index error: ' . $e->getMessage());
            return redirect()->back()->withErrors('Error loading schedules: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('doctor.schedules.create');
    }

    // 2. Modify store method to ensure consistent date/time format
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'schedule_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_patients' => 'required|integer|min:1',
            'is_available' => 'nullable|boolean',
        ]);

        try {
            $doctor = Auth::user()->doctor;
            if (!$doctor) {
                return redirect()->back()->withErrors('No associated doctor found.');
            }

            // Format dates consistently
            $scheduleDate = Carbon::parse($validatedData['schedule_date'])->format('Y-m-d');
            $startTime = Carbon::parse($validatedData['start_time'])->format('H:i');
            $endTime = Carbon::parse($validatedData['end_time'])->format('H:i');

            $schedule = Schedule::create([
                'doctor_id' => $doctor->doctor_id,
                'schedule_date' => $scheduleDate,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'max_patients' => $validatedData['max_patients'],
                'is_available' => $validatedData['is_available'] ?? true,
                'booked_patients' => 0,
                'day_of_week' => Carbon::parse($scheduleDate)->dayOfWeek
            ]);

            return redirect()
                ->route('doctor.schedules.index')
                ->with('success', 'Schedule added successfully.');
        } catch (\Exception $e) {
            Log::error('Schedule creation error: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withErrors('Failed to create schedule: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $doctor = Auth::user()->doctor;
        $schedule = $doctor->schedules()->findOrFail($id);

        return view('doctor.schedules.show', compact('schedule'));
    }


    public function edit($id)
    {
        $schedule = Schedule::findOrFail($id);

        // Check if the authenticated doctor owns the schedule
        if ($schedule->doctor_id !== Auth::user()->doctor->doctor_id) {
            return redirect()->route('doctor.schedules.index')->withErrors('You are not authorized to edit this schedule.');
        }

        return view('doctor.schedules.edit', compact('schedule'));
    }



    public function update(Request $request, $id)
    {
        try {
            $schedule = Schedule::findOrFail($id);

            // Validate input data without 'schedule_date'
            $validatedData = $request->validate([
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'max_patients' => 'required|integer|min:1',
                'is_available' => 'nullable|boolean',
            ]);

            // Set default value for is_available if unchecked
            $validatedData['is_available'] = $request->has('is_available');

            // Update only the validated fields
            $schedule->update($validatedData);

            return response()->json([
                'message' => 'Schedule updated successfully',
                'schedule' => $schedule->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error('Schedule update error:', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to update schedule: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $doctor = Auth::user()->doctor;
        $schedule = Schedule::where('doctor_id', $doctor->doctor_id)->findOrFail($id);
        $schedule->delete();

        return response()->json(['success' => true]);
    }


    public function getSchedulesForPatient($doctorId)
    {
        try {
            $schedules = Schedule::where('doctor_id', $doctorId)
                ->where('schedule_date', '>=', Carbon::now()->startOfDay())
                ->where('is_available', true)
                ->orderBy('schedule_date')
                ->orderBy('start_time')
                ->get()
                ->map(function ($schedule) {
                    return [
                        'schedule_id' => $schedule->schedule_id,
                        'schedule_date' => Carbon::parse($schedule->schedule_date)->format('Y-m-d'),
                        'start_time' => Carbon::parse($schedule->start_time)->format('H:i'),
                        'end_time' => Carbon::parse($schedule->end_time)->format('H:i'),
                        'max_patients' => (int) $schedule->max_patients,
                        'booked_patients' => (int) $schedule->booked_patients ?? 0,
                        'is_available' => (bool) $schedule->is_available
                    ];
                });

            return response()->json(['schedules' => $schedules]);
        } catch (\Exception $e) {
            Log::error('Schedule loading error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load schedules'], 500);
        }
    }

    public function toggleStatus(Schedule $schedule)
    {
        $this->authorize('update', $schedule);

        $schedule->is_available = !$schedule->is_available;
        $schedule->save();

        return redirect()
            ->route('doctor.schedules.index')
            ->with('success', 'Schedule status updated successfully.');
    }

    public function getEditData($encryptedId)
    {
        try {
            // Log the received encrypted ID for debugging
            Log::info('Received encrypted ID:', ['encrypted_id' => $encryptedId]);

            $id = Crypt::decrypt($encryptedId); // Use Crypt instead of decrypt()

            Log::info('Decrypted ID:', ['id' => $id]);

            $schedule = Schedule::findOrFail($id);

            // Check ownership
            if ($schedule->doctor_id !== Auth::user()->doctor->doctor_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            return response()->json([
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'max_patients' => $schedule->max_patients,
                'is_available' => $schedule->is_available,
                'schedule_date' => $schedule->schedule_date->format('Y-m-d'), // Format date here
            ]);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            Log::error('Decryption failed:', [
                'encrypted_id' => $encryptedId,
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Invalid encrypted ID'], 400);
        } catch (\Exception $e) {
            Log::error('Failed to load schedule data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to load schedule data'], 500);
        }
    }



    public function getSchedulesByDate($date)
    {
        $doctor = Auth::user()->doctor;
        $schedules = Schedule::where('doctor_id', $doctor->doctor_id)
            ->where('schedule_date', $date)
            ->get();

        return response()->json(['schedules' => $schedules]);
    }

    public function getScheduleEditData($id)
    {
        $doctor = Auth::user()->doctor;
        $schedule = Schedule::where('doctor_id', $doctor->doctor_id)->findOrFail($id);

        return response()->json($schedule);
    }
}
