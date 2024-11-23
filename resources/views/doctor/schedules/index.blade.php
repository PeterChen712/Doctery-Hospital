a<!-- resources/views/doctor/schedules/index.blade.php -->
@extends('layouts.doctor')


@section('content')
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">My Schedules</h2>
        <a href="{{ route('doctor.schedules.create') }}"
            class="bg-blue-500 hover:bg-blue-600 text-white font-medium px-4 py-2 rounded-md inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add Schedule
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Date
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Start Time
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            End Time
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Max Patients
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($schedules as $schedule)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $schedule->schedule_date->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $schedule->start_time }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $schedule->end_time }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $schedule->max_patients }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('doctor.schedules.edit', ['schedule' => $schedule->schedule_id]) }}"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>

                                <form
                                    action="{{ route('doctor.schedules.destroy', ['schedule' => $schedule->schedule_id]) }}"
                                    method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Are you sure you want to delete this schedule?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                No schedules found. Click "Add Schedule" to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
i>
    </template>

    <template data-template="event">
        <button class="event" data-event>
            <span class="event__color"></span>
            <span class="event__title" data-event-title></span>
            <span class="event__time">
                <time data-event-start-time></time> - <time data-event-end-time></time>
            </span>
        </button>
    </template>

    <template data-template="mini-calendar-day-list-item">
        <li class="mini-calendar__day-list-item" data-mini-calendar-day-list-item>
            <button class="mini-calendar__day button button--sm" data-mini-calendar-day></button>
        </li>
    </template>
</body>

</html>
