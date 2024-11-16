<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class ScheduleController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $schedules = Schedule::where('doctor_id', Auth::user()->doctor->doctor_id)
            ->orderBy('schedule_date')
            ->orderBy('start_time')
            ->get();

        return view('doctor.schedules.index', compact('schedules'));
    }

    public function create()
    {
        return view('doctor.schedules.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'day_of_week' => 'required|integer|between:0,6',
            'schedule_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_patients' => 'required|integer|min:1',
            'is_active' => 'boolean'
        ]);

        // Get doctor_id from linked doctors table
        $doctorId = Auth::user()->doctor->doctor_id;

        Schedule::create([
            'doctor_id' => $doctorId,
            'day_of_week' => $validated['day_of_week'],
            'schedule_date' => $validated['schedule_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'max_patients' => $validated['max_patients'],
            'is_active' => $validated['is_active'] ?? true
        ]);

        return redirect()
            ->route('doctor.schedules.index')
            ->with('success', 'Schedule created successfully');
    }

    public function edit(Schedule $schedule)
    {
        if (!Auth::user()->role == 'admin' || !Auth::user()->role == 'doctor' ) {
            abort(403);
        }
        return view('doctor.schedules.edit', compact('schedule'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        if (!Auth::user()->role == 'admin' || !Auth::user()->role == 'doctor' ) {
            abort(403);
        }

        $validated = $request->validate([
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_patients' => 'required|integer|min:1',
            'is_active' => 'boolean'
        ]);

        $schedule->update($validated);

        return redirect()
            ->route('doctor.schedules.index')
            ->with('success', 'Schedule updated successfully');
    }

    public function destroy(Schedule $schedule)
    {
        if (!Auth::user()->role == 'admin' || !Auth::user()->role == 'doctor' ) {
            abort(403);
        }

        $schedule->delete();

        return redirect()
            ->route('doctor.schedules.index')
            ->with('success', 'Schedule deleted successfully');
    }

    public function toggleStatus(Schedule $schedule)
    {
        if (!Auth::user()->role == 'admin' || !Auth::user()->role == 'doctor' ) {
            abort(403);
        }

        $schedule->update([
            'is_active' => !$schedule->is_active
        ]);

        return redirect()
            ->route('doctor.schedules.index')
            ->with('success', 'Schedule status updated successfully');
    }
}
