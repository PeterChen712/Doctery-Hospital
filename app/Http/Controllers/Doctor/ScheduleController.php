<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $doctor = Auth::user()->doctor;
        $schedules = $doctor->schedules()->orderBy('schedule_date', 'desc')->get();

        return view('doctor.schedules.index', compact('schedules'));
    }

    public function create()
    {
        return view('doctor.schedules.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_patients' => 'required|integer|min:1',
            'day_of_week' => 'required|integer|between:1,7',
        ]);

        $doctor = Auth::user()->doctor;
        $doctor->schedules()->create($validated);

        return redirect()->route('doctor.schedules.index')
            ->with('success', 'Schedule created successfully.');
    }

    public function show($id)
    {
        $doctor = Auth::user()->doctor;
        $schedule = $doctor->schedules()->findOrFail($id);

        return view('doctor.schedules.show', compact('schedule'));
    }

    public function edit($id)
    {
        $doctor = Auth::user()->doctor;
        $schedule = $doctor->schedules()->findOrFail($id);

        return view('doctor.schedules.edit', compact('schedule'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'schedule_date' => 'required|date|after_or_equal:today',
            'start_time'    => 'required|date_format:H:i',
            'end_time'      => 'required|date_format:H:i|after:start_time',
            'max_patients'  => 'required|integer|min:1',
        ]);

        $doctor = Auth::user()->doctor;
        $schedule = $doctor->schedules()->findOrFail($id);

        $schedule->update([
            'schedule_date' => $request->schedule_date,
            'start_time'    => $request->start_time,
            'end_time'      => $request->end_time,
            'max_patients'  => $request->max_patients,
        ]);

        return redirect()->route('doctor.schedules.index')
            ->with('success', 'Schedule updated successfully.');
    }

    public function destroy($id)
    {
        $doctor = Auth::user()->doctor;
        $schedule = $doctor->schedules()->findOrFail($id);

        $schedule->delete();

        return redirect()->route('doctor.schedules.index')
            ->with('success', 'Schedule deleted successfully.');
    }

    public function getAvailableSchedules($doctorId)
    {
        try {
            // Check if user has permission
            if (!Auth::check() || Auth::user()->role !== 'doctor') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $schedules = Schedule::where('doctor_id', $doctorId)
                ->where('schedule_date', '>=', now()->toDateString())
                ->where('is_available', true)
                ->get();

            return response()->json($schedules);
        } catch (\Exception $e) {
            Log::error('Schedule loading error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function toggleStatus(Schedule $schedule)
    {
        $this->authorize('update', $schedule);

        $schedule->is_active = !$schedule->is_active;
        $schedule->save();

        return redirect()
            ->route('doctor.schedules.index')
            ->with('success', 'Schedule status updated successfully.');
    }
}
