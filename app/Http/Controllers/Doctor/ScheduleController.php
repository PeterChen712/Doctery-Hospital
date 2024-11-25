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
        // Get the authenticated doctor
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            return redirect()->back()->withErrors('You are not associated with any doctor profile.');
        }

        // Get the month and year from request or default to current
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Retrieve schedules using getAvailableSchedules method
        $schedules = $this->getAvailableSchedules($doctor->doctor_id, $month, $year);

        // Organize schedules by date for easier access in the view
        $schedulesByDate = $schedules->groupBy(function ($schedule) {
            return Carbon::parse($schedule->schedule_date)->toDateString();
        });

        // Calculate total days in the month
        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;

        // Calculate the day of the week of the first day of the month (0 = Sunday, 6 = Saturday)
        $firstDayOfMonth = Carbon::createFromDate($year, $month, 1)->dayOfWeek;

        // Get previous and next months for navigation
        $currentDate = Carbon::createFromDate($year, $month, 1);
        $date = $currentDate->toDateString();
        $prevMonthDate = $currentDate->copy()->subMonth();
        $nextMonthDate = $currentDate->copy()->addMonth();

        $prevMonth = $prevMonthDate->month;
        $prevYear = $prevMonthDate->year;
        $nextMonth = $nextMonthDate->month;
        $nextYear = $nextMonthDate->year;

        // Pass variables to the view
        return view('doctor.schedules.index', compact(
            'year',
            'month',
            'schedulesByDate',
            'prevMonth',
            'prevYear',
            'nextMonth',
            'nextYear',
            'daysInMonth',
            'firstDayOfMonth',
            'date'
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
        $schedule = Schedule::findOrFail($id);

        // Check if the authenticated doctor owns the schedule
        if ($schedule->doctor_id !== Auth::user()->doctor->doctor_id) {
            return redirect()->route('doctor.schedules.index')->withErrors('You are not authorized to edit this schedule.');
        }

        return view('doctor.schedules.edit', compact('schedule'));
    }


    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        // Check ownership
        if ($schedule->doctor_id !== Auth::user()->doctor->doctor_id) {
            return redirect()->route('doctor.schedules.index')->withErrors('You are not authorized to update this schedule.');
        }

        // Validate input
        $validatedData = $request->validate([
            'schedule_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'max_patients' => 'required|integer|min:1',
            'is_available' => 'sometimes|boolean',
        ]);

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

            return $schedules;
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


    public function getEditData($id)
    {
        $schedule = Schedule::findOrFail($id);

        // Check if the authenticated doctor owns the schedule
        if ($schedule->doctor_id !== Auth::user()->doctor->doctor_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($schedule);
    }
}
