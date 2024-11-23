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

    <!-- Calendar Component -->
    <div class="bg-white shadow rounded-lg p-4">
        <!-- Calendar Header -->
        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('doctor.schedules.index', ['month' => $prevMonth, 'year' => $prevYear]) }}"
                class="text-gray-600 hover:text-gray-800">
                &lt;
            </a>
            <h3 class="text-lg font-semibold">
                {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}
            </h3>
            <a href="{{ route('doctor.schedules.index', ['month' => $nextMonth, 'year' => $nextYear]) }}"
                class="text-gray-600 hover:text-gray-800">
                &gt;
            </a>
        </div>

        <!-- Week Days -->
        <div class="grid grid-cols-7 gap-2 text-center">
            <div class="font-medium">Sun</div>
            <div class="font-medium">Mon</div>
            <div class="font-medium">Tue</div>
            <div class="font-medium">Wed</div>
            <div class="font-medium">Thu</div>
            <div class="font-medium">Fri</div>
            <div class="font-medium">Sat</div>
        </div>

        <!-- Calendar Grid -->
        <div class="grid grid-cols-7 gap-2 mt-2">
            <!-- Blank cells for previous month days -->
            @for ($i = 0; $i < $firstDayOfMonth; $i++)
                <div class="border p-4 bg-gray-100"></div>
            @endfor

            <!-- Days of the month -->
            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $date = \Carbon\Carbon::create($year, $month, $day)->format('Y-m-d');
                    $schedulesForDay = $schedules->where('schedule_date', $date);
                    $isToday = $date === \Carbon\Carbon::today()->format('Y-m-d');
                @endphp
                <div class="border p-2 h-32 overflow-auto relative group {{ $isToday ? 'bg-yellow-100' : 'bg-white' }}">
                    <div class="font-semibold mb-1">{{ $day }}</div>
                    @foreach ($schedulesForDay as $schedule)
                        <div class="mt-1 p-1 bg-blue-50 rounded relative">
                            <div class="text-sm font-medium">
                                {{ $schedule->start_time }} - {{ $schedule->end_time }}
                            </div>
                            <div class="text-xs">Max Patients: {{ $schedule->max_patients }}</div>
                            <div class="text-xs text-gray-600">
                                {{ $schedule->is_available ? 'Available' : 'Not Available' }}
                            </div>
                            <!-- Edit button -->
                            <button class="absolute top-0 right-0 mt-1 mr-1 text-gray-500 hover:text-blue-500"
                                onclick="openEditModal({{ $schedule->schedule_id }})">
                                ✎
                            </button>
                        </div>
                    @endforeach
                    <!-- Add Schedule Button -->
                    <button class="opacity-0 group-hover:opacity-100 absolute bottom-1 right-1 text-blue-500 text-sm"
                        onclick="openAddModal('{{ $date }}')">
                        + Add
                    </button>
                </div>
            @endfor

            <!-- Fill the remaining cells -->
            @php
                $totalCells = $firstDayOfMonth + $daysInMonth;
                $remainingCells = ceil($totalCells / 7) * 7 - $totalCells;
            @endphp
            @for ($i = 0; $i < $remainingCells; $i++)
                <div class="border p-4 bg-gray-100"></div>
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
                    <input type="time" name="start_time" id="editStartTime" class="w-full border rounded p-2" required>
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
            fetch(`/doctor/schedules/${scheduleId}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editDate').value = data.schedule_date;
                    document.getElementById('editStartTime').value = data.start_time;
                    document.getElementById('editEndTime').value = data.end_time;
                    document.getElementById('editMaxPatients').value = data.max_patients;
                    document.getElementById('editIsAvailable').checked = data.is_available;
                    document.getElementById('editForm').action = `/doctor/schedules/${scheduleId}`;
                    document.getElementById('editModal').classList.remove('hidden');
                });
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
@endsection
