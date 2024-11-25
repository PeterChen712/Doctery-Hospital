@extends('layouts.doctor')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">My Schedules</h2>
        Today's Date: {{ $date }}
        <a href="{{ route('doctor.schedules.create') }}"
            class="bg-blue-500 hover:bg-blue-600 text-white font-medium px-4 py-2 rounded-md inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add Schedule
        </a>
    </div>

    <!-- Calendar Component -->
    <div class="bg-white shadow rounded-lg p-4">
        <!-- Calendar Header -->
        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('doctor.schedules.index', ['month' => $prevMonth, 'year' => $prevYear]) }}"
                class="text-gray-600 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h3 class="text-lg font-semibold">
                {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}
            </h3>
            <a href="{{ route('doctor.schedules.index', ['month' => $nextMonth, 'year' => $nextYear]) }}"
                class="text-gray-600 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <!-- Week Days -->
        <div class="grid grid-cols-7 gap-2 text-center mb-2">
            @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dayName)
                <div class="font-medium text-gray-700 py-2 bg-gray-50 rounded">{{ $dayName }}</div>
            @endforeach
        </div>

        <!-- Calendar Grid -->
        <div class="grid grid-cols-7 gap-2">
            <!-- Blank cells for previous month days -->
            @for ($i = 0; $i < $firstDayOfMonth; $i++)
                <div class="border border-gray-200 p-4 bg-gray-50 rounded-lg"></div>
            @endfor

            <!-- Days of the month -->
            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $date = \Carbon\Carbon::createFromDate($year, $month, $day)->toDateString();
                    $schedulesForDay = $schedulesByDate->get($date, collect());
                    $isToday = $date === \Carbon\Carbon::today()->toDateString();
                    $hasSchedules = $schedulesForDay->isNotEmpty();
                    $firstSchedule = $schedulesForDay->first();
                @endphp
                <div
                    class="border border-gray-200 p-2 h-36 overflow-auto relative group rounded-lg transition-all duration-200 hover:shadow-md
     {{ $isToday ? 'bg-yellow-50 border-yellow-200' : 'bg-white' }}">
                    <!-- Date Number -->
                    <div class="font-semibold mb-2 {{ $isToday ? 'text-yellow-600' : 'text-gray-700' }}">
                        {{ $day }}
                    </div>

                    <!-- Green Dot Indicator -->
                    @if ($hasSchedules)
                    <a href="{{ route('doctor.schedules.edit', $firstSchedule->schedule_id) }}"                             
                        title="Edit Schedule">                             
                         <span class="absolute top-2 right-2 bg-green-500 w-3 h-3 rounded-full"></span>                         
                     </a>
                    @endif

                    <!-- Schedules for the day -->
                    <div class="space-y-1">
                        @foreach ($schedulesForDay as $schedule)
                            <div
                                class="p-1.5 rounded-md relative transition-all duration-200
                        {{ $schedule->is_available
                            ? 'bg-green-50 border border-green-100 hover:bg-green-100'
                            : 'bg-red-50 border border-red-100 hover:bg-red-100' }}">

                                <div
                                    class="text-sm font-medium {{ $schedule->is_available ? 'text-green-700' : 'text-red-700' }}">
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                </div>

                                <div class="text-xs {{ $schedule->is_available ? 'text-green-600' : 'text-red-600' }}">
                                    Patients: {{ $schedule->booked_patients ?? 0 }}/{{ $schedule->max_patients }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Add Schedule Button -->
                    <button
                        class="opacity-0 group-hover:opacity-100 absolute bottom-1 right-1 
            bg-blue-50 hover:bg-blue-100 text-blue-600 hover:text-blue-700 
            rounded-full p-1.5 transition-all duration-200"
                        onclick="openAddModal('{{ $date }}')" title="Add Schedule">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </button>
                </div>
            @endfor

            <!-- Fill remaining cells -->
            @php
                $totalCells = $firstDayOfMonth + $daysInMonth;
                $remainingCells = ceil($totalCells / 7) * 7 - $totalCells;
            @endphp
            @for ($i = 0; $i < $remainingCells; $i++)
                <div class="border border-gray-200 p-4 bg-gray-50 rounded-lg"></div>
            @endfor
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg w-96 relative">
            <button type="button" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800"
                onclick="closeAddModal()">&times;</button>
            <h3 class="text-lg font-semibold mb-4">Add Schedule</h3>
            <form id="addForm" method="POST" action="{{ route('doctor.schedules.store') }}">
                @csrf
                <!-- Hidden date field -->
                <input type="hidden" name="schedule_date" id="addDate">
                <!-- Form fields -->
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Start Time</label>
                    <input type="time" name="start_time" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">End Time</label>
                    <input type="time" name="end_time" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Max Patients</label>
                    <input type="number" name="max_patients" min="1" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4 flex items-center">
                    <input type="checkbox" name="is_available" id="is_available" value="1" checked class="mr-2">
                    <label for="is_available" class="text-sm font-medium">Is Available</label>
                </div>
                <!-- Buttons -->
                <div class="flex justify-end">
                    <button type="button" onclick="closeAddModal()" class="px-4 py-2 mr-2">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg w-96 relative">
            <button class="absolute top-2 right-2 text-gray-600 hover:text-gray-800"
                onclick="closeEditModal()">&times;</button>
            <h3 class="text-lg font-semibold mb-4">Edit Schedule</h3>

            <form id="editForm" method="POST" action="">
                @csrf
                @method('PUT')
                <!-- Hidden date field -->
                <input type="hidden" name="schedule_date" id="editDate">
                <!-- Form fields -->
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Start Time</label>
                    <input type="time" name="start_time" id="editStartTime" class="w-full border rounded p-2"
                        required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">End Time</label>
                    <input type="time" name="end_time" id="editEndTime" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Max Patients</label>
                    <input type="number" name="max_patients" id="editMaxPatients" min="1"
                        class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4 flex items-center">
                    <input type="checkbox" name="is_available" id="editIsAvailable" value="1" class="mr-2">
                    <label for="editIsAvailable" class="text-sm font-medium">Is Available</label>
                </div>
                <!-- Buttons -->
                <div class="flex justify-end">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 mr-2">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript Functions -->
    <script>
        function openAddModal(date) {
            document.getElementById('addDate').value = date;
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function openEditModal(scheduleId) {
            // Fetch schedule data via AJAX
            fetch(`/doctor/schedules/${scheduleId}/edit-data`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editDate').value = data.schedule_date;
                    document.getElementById('editStartTime').value = data.start_time;
                    document.getElementById('editEndTime').value = data.end_time;
                    document.getElementById('editMaxPatients').value = data.max_patients;
                    document.getElementById('editIsAvailable').checked = data.is_available == 1;
                    document.getElementById('editForm').action = `/doctor/schedules/${scheduleId}`;
                    document.getElementById('editModal').classList.remove('hidden');
                });
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
@endsection
