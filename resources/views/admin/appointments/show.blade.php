<!-- resources/views/admin/appointments/show.blade.php -->
@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-3xl mx-auto">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <!-- Previous code remains same until doctor assignment form -->

                    <div class="mt-6 grid grid-cols-2 gap-6">
                        <!-- Patient Info -->
                        <div>
                            <h3 class="text-gray-600">Patient</h3>
                            <p class="font-semibold">{{ $appointment->patient->user->username }}</p>
                        </div>

                        <!-- Doctor Assignment -->
                        <div>
                            <h3 class="text-gray-600">Doctor Assignment</h3>
                            <form id="appointmentForm" action="{{ route('admin.appointments.assign-doctor', $appointment) }}"
                                method="POST" class="mt-1">
                                @csrf
                                @method('PUT')
                                <select name="doctor_id" id="doctor_id" class="rounded border-gray-300 w-full"
                                    onchange="loadDoctorSchedules()">
                                    <option value="">Select Doctor</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->doctor_id }}"
                                            {{ $appointment->doctor_id == $doctor->doctor_id ? 'selected' : '' }}>
                                            {{ $doctor->user->username }}
                                        </option>
                                    @endforeach
                                </select>

                                <!-- Schedule Selection (populated via JS) -->
                                <div class="mt-4">
                                    <h3 class="text-gray-600">Schedule</h3>
                                    <select name="schedule_id" id="schedule_id" class="rounded border-gray-300 w-full"
                                        disabled>
                                        <option value="">Select Doctor First</option>
                                    </select>
                                </div>


                                <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded text-sm">
                                    Assign Doctor & Schedule
                                </button>
                            </form>
                        </div>

                        <!-- Other details remain same -->
                    </div>

                    <!-- Footer Buttons -->
                    <div class="mt-8 flex justify-between">
                        <a href="{{ route('admin.appointments.index') }}"
                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                            Back to List
                        </a>
                        <button form="appointmentForm" type="submit"
                            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function loadDoctorSchedules() {
            const doctorId = document.getElementById('doctor_id').value;
            const scheduleSelect = document.getElementById('schedule_id');
            const token = document.querySelector('meta[name="csrf-token"]').content;

            console.log('Doctor ID:', doctorId); // Debug log

            if (!doctorId) {
                scheduleSelect.innerHTML = '<option value="">Select Doctor First</option>';
                scheduleSelect.disabled = true;
                return;
            }

            console.log('Fetching schedules...'); // Debug log
            fetch(`/admin/doctors/${doctorId}/schedules`, {
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Schedules:', data); // Debug log
                    scheduleSelect.innerHTML = '<option value="">Select Schedule</option>';

                    data.schedules.forEach(schedule => {
                        const option = document.createElement('option');
                        option.value = schedule.schedule_id;

                        try {
                            // Parse date with fallback
                            let scheduleDate;
                            if (schedule.schedule_date.includes('T')) {
                                // If ISO format
                                scheduleDate = new Date(schedule.schedule_date);
                            } else {
                                // If YYYY-MM-DD format
                                scheduleDate = new Date(schedule.schedule_date + 'T00:00:00');
                            }

                            if (isNaN(scheduleDate)) {
                                throw new Error('Invalid date');
                            }

                            const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday',
                                'Saturday'
                            ];
                            const formattedDate = scheduleDate.toLocaleDateString();
                            const dayName = dayNames[schedule.day_of_week];

                            // Format times
                            const formatTime = (timeStr) => {
                                if (!timeStr) return '';
                                const [hours, minutes] = timeStr.split(':');
                                return new Date(2000, 0, 1, hours, minutes).toLocaleTimeString([], {
                                    hour: 'numeric',
                                    minute: '2-digit'
                                });
                            };

                            option.textContent =
                                `${dayName} (${formattedDate}) ${formatTime(schedule.start_time)} - ${formatTime(schedule.end_time)}`;
                        } catch (error) {
                            console.error('Date parsing error:', error);
                            option.textContent = `Schedule ${schedule.schedule_id} (Invalid Date)`;
                        }

                        scheduleSelect.appendChild(option);
                    });

                    scheduleSelect.disabled = false;
                })
        }

        // Load schedules if doctor is already selected
        if (document.getElementById('doctor_id').value) {
            loadDoctorSchedules();
        }
    </script>
@endsection
