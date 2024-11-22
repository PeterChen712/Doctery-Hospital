<!-- resources/views/doctor/schedules/create.blade.php -->
@extends('layouts.doctor')

@section('header')
    <h2 class="text-xl font-semibold">Create New Schedule</h2>
@endsection

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <form action="{{ route('doctor.schedules.store') }}" method="POST">
                @csrf

                <!-- Schedule Date -->
                <div class="mb-4">
                    <label for="schedule_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Schedule Date</label>
                    <input type="date" name="schedule_date" id="schedule_date" 
                           value="{{ old('schedule_date', now()->format('Y-m-d')) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('schedule_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Day Selection -->
                <div class="mb-4">
                    <label for="day_of_week" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Day of Week</label>
                    <select name="day_of_week" id="day_of_week" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="1">Monday</option>
                        <option value="2">Tuesday</option>
                        <option value="3">Wednesday</option>
                        <option value="4">Thursday</option>
                        <option value="5">Friday</option>
                        <option value="6">Saturday</option>
                        <option value="7">Sunday</option>
                    </select>
                    @error('day_of_week')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Time Range -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Time</label>
                        <input type="time" name="start_time" id="start_time" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Time</label>
                        <input type="time" name="end_time" id="end_time" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Max Patients -->
                <div class="mb-4">
                    <label for="max_patients" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Maximum Patients</label>
                    <input type="number" name="max_patients" id="max_patients" min="1" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('max_patients')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="mb-6">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_active" value="1" checked 
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Active Schedule</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                        Create Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection