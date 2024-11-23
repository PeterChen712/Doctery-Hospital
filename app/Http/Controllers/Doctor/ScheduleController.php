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

    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('n'));

        $date = Carbon::create($year, $month, 1);

        $firstDayOfMonth = $date->dayOfWeek; // 0 (Sunday) to 6 (Saturday)
        $daysInMonth = $date->daysInMonth;

        // Previous and Next Month
        $prevMonthDate = $date->copy()->subMonth();
        $nextMonthDate = $date->copy()->addMonth();

        $prevMonth = $prevMonthDate->month;
        $prevYear = $prevMonthDate->year;
        $nextMonth = $nextMonthDate->month;
        $nextYear = $nextMonthDate->year;

        // Fetch schedules for the authenticated doctor
        $schedules = Schedule::where('doctor_id', Auth::id())
            ->whereYear('schedule_date', $year)
            ->whereMonth('schedule_date', $month)
            ->get();

        return view('doctor.schedules.index', compact(
            'year',
            'month',
            'firstDayOfMonth',
            'daysInMonth',
            'schedules',
            'prevMonth',
            'prevYear',
            'nextMonth',
            'nextYear'
        ));
    }

    public function create()
    {
        return view('doctor.schedules.create');
    }

    public function store(Request $request)
    {
        // Validate input data
        $validatedData = $request->validate([
            'schedule_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_patients' => 'required|integer|min:1',
            'is_available' => 'nullable|boolean',
        ]);

        // Set default value for is_available if unchecked
        $validatedData['is_available'] = $request->has('is_available');

        // Retrieve the doctor associated with the authenticated user
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            return redirect()->back()->withErrors('No associated doctor found for the authenticated user.');
        }

        // Add doctor_id and day_of_week
        $validatedData['doctor_id'] = $doctor->doctor_id;
        $validatedData['day_of_week'] = Carbon::parse($validatedData['schedule_date'])->dayOfWeek;

        // Create schedule
        Schedule::create($validatedData);

        return redirect()->route('doctor.schedules.index')->with('success', 'Schedule added successfully.');
    }

    public function show($id)
    {
        $doctor = Auth::user()->doctor;
        $schedule = $doctor->schedules()->findOrFail($id);

        return view('doctor.schedules.show', compact('schedule'));
    }


    public function edit($id)
    {
        $schedule = Schedule::where('doctor_id', Auth::id())->findOrFail($id);

        return response()->json($schedule);
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::where('doctor_id', Auth::id())->findOrFail($id);

        // Validate input data
        $validatedData = $request->validate([
            'schedule_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_patients' => 'required|integer|min:1',
            'is_available' => 'nullable|boolean',
        ]);

        // Set default value for is_available if unchecked
        $validatedData['is_available'] = $request->has('is_available');

        // Update schedule
        $schedule->update($validatedData);

        return redirect()->route('doctor.schedules.index')->with('success', 'Schedule updated successfully.');
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

        $schedule->is_available = !$schedule->is_available;
        $schedule->save();

        return redirect()
            ->route('doctor.schedules.index')
            ->with('success', 'Schedule status updated successfully.');
    }
}
