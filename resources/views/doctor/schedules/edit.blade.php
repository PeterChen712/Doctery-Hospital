@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6">Edit Schedule</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('doctor.schedules.update', $schedule->schedule_id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Schedule Date -->
            <div class="mb-4">
                <label for="schedule_date" class="block font-semibold mb-1">Schedule Date</label>
                <input type="date" name="schedule_date" id="schedule_date"
                    value="{{ old('schedule_date', $schedule->schedule_date) }}" class="w-full border-gray-300 rounded">
            </div>

            <!-- Start Time -->
            <div class="mb-4">
                <label for="start_time" class="block font-semibold mb-1">Start Time</label>
                <input type="time" name="start_time" id="start_time"
                    value="{{ old('start_time', $schedule->start_time) }}" class="w-full border-gray-300 rounded">
            </div>

            <!-- End Time -->
            <div class="mb-4">
                <label for="end_time" class="block font-semibold mb-1">End Time</label>
                <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $schedule->end_time) }}"
                    class="w-full border-gray-300 rounded">
            </div>

            <!-- Max Patients -->
            <div class="mb-4">
                <label for="max_patients" class="block font-semibold mb-1">Maximum Patients</label>
                <input type="number" name="max_patients" id="max_patients"
                    value="{{ old('max_patients', $schedule->max_patients) }}" class="w-full border-gray-300 rounded"
                    min="1">
            </div>

            <!-- Availability -->
            <div class="mb-6">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_available" value="1" class="form-checkbox"
                        {{ old('is_available', $schedule->is_available) ? 'checked' : '' }}>
                    <span class="ml-2">Is Available</span>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <a href="{{ route('doctor.schedules.index') }}" class="mr-4 text-gray-600 hover:text-gray-800">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update Schedule</button>
            </div>
        </form>
    </div>
@endsection
