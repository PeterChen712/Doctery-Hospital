<!-- resources/views/patient/appointments/create.blade.php -->
@extends('layouts.patient')

@section('content')
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-semibold mb-4">Request an Appointment</h2>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('patient.appointments.store') }}" method="POST">
            @csrf

            <!-- Doctor Selection -->
            <div class="mb-4">
                <label for="doctor_id" class="block text-gray-700 font-bold mb-2">Select Doctor:</label>
                <select name="doctor_id" id="doctor_id" class="w-full px-3 py-2 border rounded-lg"
                    onchange="loadDoctorSchedules()" required>
                    <option value="">Choose a doctor...</option>
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->doctor_id }}" data-specialization="{{ $doctor->specialization }}">
                            Dr. {{ $doctor->user->username }} - {{ $doctor->specialization }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Appointment Type -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Appointment Type:</label>
                <div class="flex gap-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="appointment_type" value="scheduled" checked
                            class="form-radio text-blue-500" onchange="toggleAppointmentType()">
                        <span class="ml-2">Available Schedule</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="appointment_type" value="preferred" class="form-radio text-blue-500"
                            onchange="toggleAppointmentType()">
                        <span class="ml-2">Preferred Schedule</span>
                    </label>
                </div>
            </div>

            <!-- Schedule Selection (For Available Schedule) -->
            <div id="scheduled-section">
                <div class="mb-4">
                    <label for="schedule_id" class="block text-gray-700 font-bold mb-2">Available Schedule:</label>
                    <select name="schedule_id" id="schedule_id" class="w-full px-3 py-2 border rounded-lg" required
                        disabled>
                        <option value="">Select a doctor first...</option>
                    </select>
                </div>
            </div>

            <!-- Preferred Schedule Section -->
            <div id="preferred-section" class="hidden">
                <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="preferred_date" class="block text-gray-700 font-bold mb-2">Preferred Date:</label>
                        <input type="date" name="preferred_date" id="preferred_date"
                            class="w-full px-3 py-2 border rounded-lg" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    </div>
                    <div>
                        <label for="preferred_time" class="block text-gray-700 font-bold mb-2">Preferred Time:</label>
                        <select name="preferred_time" id="preferred_time" class="w-full px-3 py-2 border rounded-lg">
                            <option value="">Select time...</option>
                            <option value="morning">Morning (9:00 AM - 12:00 PM)</option>
                            <option value="afternoon">Afternoon (1:00 PM - 5:00 PM)</option>
                            <option value="evening">Evening (6:00 PM - 8:00 PM)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Reason -->
            <div class="mb-4">
                <label for="reason" class="block text-gray-700 font-bold mb-2">Reason for Visit:</label>
                <textarea name="reason" id="reason" rows="3" class="w-full px-3 py-2 border rounded-lg" required></textarea>
            </div>

            <!-- Symptoms -->
            <div class="mb-4">
                <label for="symptoms" class="block text-gray-700 font-bold mb-2">Describe Your Symptoms:</label>
                <textarea name="symptoms" id="symptoms" rows="3" class="w-full px-3 py-2 border rounded-lg"></textarea>
            </div>

            <!-- Notes -->
            <div class="mb-4">
                <label for="notes" class="block text-gray-700 font-bold mb-2">Additional Notes (Optional):</label>
                <textarea name="notes" id="notes" rows="2" class="w-full px-3 py-2 border rounded-lg"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                    Submit Request
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleAppointmentType() {
            const appointmentType = document.querySelector('input[name="appointment_type"]:checked').value;
            const scheduledSection = document.getElementById('scheduled-section');
            const preferredSection = document.getElementById('preferred-section');
            const scheduleSelect = document.getElementById('schedule_id');
            const preferredDate = document.getElementById('preferred_date');
            const preferredTime = document.getElementById('preferred_time');

            if (appointmentType === 'scheduled') {
                scheduledSection.classList.remove('hidden');
                preferredSection.classList.add('hidden');
                scheduleSelect.required = true;
                preferredDate.required = false;
                preferredTime.required = false;
            } else {
                scheduledSection.classList.add('hidden');
                preferredSection.classList.remove('hidden');
                scheduleSelect.required = false;
                preferredDate.required = true;
                preferredTime.required = true;
            }
        }

        function loadDoctorSchedules() {
            const doctorId = document.getElementById('doctor_id').value;
            const scheduleSelect = document.getElementById('schedule_id');
            const token = document.querySelector('meta[name="csrf-token"]').content;

            console.log('Doctor ID:', doctorId);

            if (!doctorId) {
                scheduleSelect.innerHTML = '<option value="">Select Doctor First</option>';
                scheduleSelect.disabled = true;
                return;
            }

            scheduleSelect.disabled = true;
            scheduleSelect.innerHTML = '<option value="">Loading schedules...</option>';

            fetch(`/patient/doctors/${doctorId}/schedules`, {
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Schedules:', data);
                    scheduleSelect.innerHTML = '<option value="">Select Schedule</option>';

                    data.schedules.forEach(schedule => {
                        const option = document.createElement('option');
                        option.value = schedule.schedule_id;

                        try {
                            let scheduleDate;
                            if (schedule.schedule_date.includes('T')) {
                                scheduleDate = new Date(schedule.schedule_date);
                            } else {
                                scheduleDate = new Date(schedule.schedule_date + 'T00:00:00');
                            }

                            if (isNaN(scheduleDate)) {
                                throw new Error('Invalid date');
                            }

                            const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday',
                                'Saturday'
                            ];
                            const formattedDate = scheduleDate.toLocaleDateString();
                            const dayName = dayNames[scheduleDate.getDay()];

                            const formatTime = (timeStr) => {
                                if (!timeStr) return '';
                                const [hours, minutes] = timeStr.split(':');
                                return new Date(2000, 0, 1, hours, minutes).toLocaleTimeString([], {
                                    hour: 'numeric',
                                    minute: '2-digit'
                                });
                            };

                            const availableSlots = schedule.max_patients - schedule.booked_patients;
                            const slotText = availableSlots > 0 ? `(${availableSlots} slots available)` :
                                '(Full)';

                            option.textContent =
                                `${dayName} (${formattedDate}) ${formatTime(schedule.start_time)} - ${formatTime(schedule.end_time)} ${slotText}`;

                            if (availableSlots <= 0) {
                                option.disabled = true;
                            }

                        } catch (error) {
                            console.error('Date parsing error:', error);
                            option.textContent = `Schedule ${schedule.schedule_id} (Invalid Date)`;
                            option.disabled = true;
                        }

                        scheduleSelect.appendChild(option);
                    });

                    scheduleSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    scheduleSelect.innerHTML = '<option value="">Error loading schedules</option>';
                    scheduleSelect.disabled = true;
                });
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            toggleAppointmentType();
            if (document.getElementById('doctor_id').value) {
                loadDoctorSchedules();
            }
        });
    </script>
@endsection
