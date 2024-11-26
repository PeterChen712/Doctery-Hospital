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
                    $isPastDate = \Carbon\Carbon::parse($date)->isPast();
                    $hasSchedules = $schedulesForDay->isNotEmpty();
                @endphp
                <div
                    class="border border-gray-200 p-2 h-36 overflow-auto relative group rounded-lg transition-all duration-200 hover:shadow-md
     {{ $isToday ? 'bg-yellow-50 border-yellow-200' : 'bg-white' }} {{ $isPastDate ? 'opacity-50' : '' }}">
                    <!-- Date Number -->
                    <div class="font-semibold mb-2 {{ $isToday ? 'text-yellow-600' : 'text-gray-700' }}">
                        {{ $day }}
                    </div>

                    <!-- Green Dot Indicator -->
                    @if ($hasSchedules)
                        <a href="#" onclick="handleScheduleClick('{{ $date }}'); return false;"
                            title="Edit Schedule">
                            <span class="absolute top-2 right-2 bg-green-500 w-3 h-3 rounded-full"></span>
                        </a>
                    @endif

                    <!-- Schedules for the day -->
                    <!-- Schedules for the day -->
                    <div class="space-y-1">
                        @foreach ($schedulesForDay as $schedule)
                            <div
                                class="p-1.5 rounded-md relative transition-all duration-200
            {{ $schedule->is_available
                ? 'bg-green-50 border border-green-100 hover:bg-green-100'
                : 'bg-gray-100 border border-gray-200 hover:bg-gray-200' }}">

                                <div
                                    class="text-sm font-medium 
                {{ $schedule->is_available ? 'text-green-700' : 'text-gray-700' }}">
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                </div>

                                <div class="text-xs {{ $schedule->is_available ? 'text-green-600' : 'text-gray-600' }}">
                                    Patients: {{ $schedule->booked_patients ?? 0 }}/{{ $schedule->max_patients }}
                                    @if (!$schedule->is_available)
                                        <span class="ml-1">(Unavailable)</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Add Schedule Button -->
                    @if (!$isPastDate)
                        <button
                            class="opacity-0 group-hover:opacity-100 sticky bottom-1 right-1 
            bg-blue-50 hover:bg-blue-100 text-blue-600 hover:text-blue-700 
            rounded-full p-1.5 transition-all duration-200"
                            onclick="openAddModal('{{ $date }}')" title="Add Schedule">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </button>
                    @endif
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
            <button type="button" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800"
                onclick="closeEditModal()">&times;</button>
            <h3 class="text-lg font-semibold mb-4">Edit Schedule</h3>

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editScheduleId">
                <!-- Add readonly attribute to schedule_date -->
                {{-- <input type="hidden" name="schedule_date" id="editDate" readonly> --}}
                <input type="hidden" id="editDate" value="">

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

                <div class="flex justify-end">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 mr-2">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save Changes</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Schedule Selection Modal -->
    <div id="scheduleSelectionModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg w-96 relative">
            <button class="absolute top-2 right-2 text-gray-600 hover:text-gray-800"
                onclick="closeScheduleSelectionModal()">&times;</button>
            <h3 class="text-lg font-semibold mb-4">Select Schedule to Edit</h3>
            <div id="scheduleList" class="space-y-2">
                <!-- Schedule options will be populated here -->
            </div>
        </div>
    </div>

    <!-- JavaScript Functions -->
    <script>
        // Modal Control Functions
        function openAddModal(date = null) {
            if (date) {
                if (new Date(date) < new Date().setHours(0, 0, 0, 0)) {
                    alert('Cannot add schedules for past dates.');
                    return;
                }
                document.getElementById('addDate').value = date;
            }
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function openEditModal(scheduleId) {
            console.log('Opening edit modal with schedule ID:', scheduleId);

            if (!scheduleId) {
                console.error('No schedule ID provided');
                return;
            }

            fetch(`/doctor/schedules/${scheduleId}/edit-data`, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to load schedule data');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Received schedule data:', data);
                    document.getElementById('editScheduleId').value = scheduleId;
                    document.getElementById('editStartTime').value = data.start_time;
                    document.getElementById('editEndTime').value = data.end_time;
                    document.getElementById('editMaxPatients').value = data.max_patients;
                    document.getElementById('editIsAvailable').checked = data.is_available;
                    // Keep the original schedule_date
                    document.getElementById('editDate').value = data.schedule_date;
                    document.getElementById('editModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load schedule data');
                });
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function handleScheduleClick(date) {
            console.log('Fetching schedules for date:', date);

            fetch(`/doctor/schedules/by-date/${date}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Failed to fetch schedules');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Fetched Schedules:', data);

                    if (!data.schedules || data.schedules.length === 0) {
                        console.log('No schedules found for date:', date);
                        return;
                    }

                    if (data.schedules.length === 1) {
                        const schedule = data.schedules[0];
                        console.log("Hasil : " + schedule.schedule_id)
                        if (schedule.schedule_id) {
                            openEditModal(schedule.schedule_id);
                        } else {
                            throw new Error('Schedule ID not found in response');
                        }
                    } else {
                        showScheduleSelectionModal(data.schedules);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message || 'Failed to fetch schedules');
                });
        }

        function showScheduleSelectionModal(schedules) {
            const scheduleList = document.getElementById('scheduleList');
            scheduleList.innerHTML = '';

            schedules.forEach(schedule => {
                const startTime = schedule.start_time.substring(0, 5); // Format HH:mm
                const endTime = schedule.end_time.substring(0, 5); // Format HH:mm

                const scheduleItem = document.createElement('div');
                scheduleItem.className = 'p-2 border rounded cursor-pointer hover:bg-gray-100 mb-2';

                // Ensure schedule.schedule_id is properly accessed
                const scheduleId = schedule.schedule_id || schedule
                    .schedule_id; // Fallback if id field name differs

                scheduleItem.innerHTML = `
            <div class="font-medium">${startTime} - ${endTime}</div>
            <div class="text-sm text-gray-600">
                Patients: ${schedule.booked_patients || 0}/${schedule.max_patients}
            </div>
            <div class="text-sm">${schedule.is_available ? 'Available' : 'Unavailable'}</div>
            <div class="mt-2">
                <button onclick="handleEditClick(${scheduleId})" 
                    class="text-blue-500 hover:text-blue-700 underline mr-2">
                    Edit
                </button>
                <button onclick="deleteSchedule(${scheduleId})" 
                    class="text-red-500 hover:text-red-700 underline">
                    Delete
                </button>
            </div>
        `;
                scheduleList.appendChild(scheduleItem);
            });

            document.getElementById('scheduleSelectionModal').classList.remove('hidden');
        }


        // Add this new function to handle edit click from selection modal

        // Update handleEditClick function
        function handleEditClick(scheduleId) {
            if (!scheduleId) {
                console.error('No schedule ID provided');
                alert('Error: Invalid schedule selected');
                return;
            }

            closeScheduleSelectionModal();
            openEditModal(scheduleId);
        }

        function closeScheduleSelectionModal() {
            document.getElementById('scheduleSelectionModal').classList.add('hidden');
        }

        function deleteSchedule(scheduleId) {
            if (confirm('Are you sure you want to delete this schedule?')) {
                fetch(`/doctor/schedules/${scheduleId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            location.reload();
                        } else {
                            alert('Failed to delete schedule');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to delete schedule');
                    });
            }
        }

        // Form Submission
        document.getElementById('addForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            fetch('{{ route('doctor.schedules.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                })
                .then(response => {
                    if (response.ok) {
                        closeAddModal();
                        location.reload();
                    } else {
                        response.json().then(data => {
                            alert('Error: ' + JSON.stringify(data.errors));
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to add schedule');
                });
        });

        // Replace your current editForm event listener with this:
        document.getElementById('editForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const scheduleId = document.getElementById('editScheduleId').value;
            // Get the original date and ensure it's in YYYY-MM-DD format
            const originalDate = document.getElementById('editDate').value;
            console.log('Original date before submit:', originalDate); // Debug log

            if (!scheduleId) {
                console.error('No schedule ID found');
                alert('Error: No schedule ID found');
                return;
            }

            const formData = new FormData(this);
            formData.append('_method', 'PUT');

            // Remove any existing schedule_date and set it fresh
            formData.delete('schedule_date');
            formData.append('schedule_date', originalDate);

            // Log the final form data
            console.log('Form data schedule_date:', formData.get('schedule_date'));

            fetch(`/doctor/schedules/${scheduleId}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Failed to update schedule');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Update response:', data); // Debug log
                    if (data.schedule && data.schedule.schedule_date !== originalDate) {
                        console.warn('Date mismatch after update:', {
                            original: originalDate,
                            returned: data.schedule.schedule_date
                        });
                    }
                    alert('Schedule updated successfully');
                    closeEditModal();
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message || 'Failed to update schedule');
                });
        });
    </script>
@endsection
