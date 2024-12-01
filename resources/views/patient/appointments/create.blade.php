<!-- resources/views/patient/appointments/create.blade.php -->
@extends('layouts.patient')

@section('content')



    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header with gradient background -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 mb-8 shadow-lg">
            <h2 class="text-2xl font-bold text-white">Buat Janji Temu</h2>
            <p class="text-blue-100 mt-2">Silakan isi formulir di bawah ini</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('patient.appointments.store') }}" method="POST"
            class="bg-white rounded-xl shadow-lg p-6 space-y-6">
            @csrf

            <!-- Doctor Selection with cool hover effect -->

            <div class="group">
                <label for="doctor_id" class="block text-gray-700 font-semibold mb-2">
                    Pilih Dokter:
                </label>
                <select id="doctor_id" name="doctor_id" class="doctor-select" required>
                    <option value="">Cari dokter...</option>
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->doctor_id }}" data-specialization="{{ $doctor->specialization }}"
                            data-image="{{ $doctor->user->profile_photo_url }}">
                            {{ $doctor->user->username }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Appointment Type with modern radio buttons -->
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



            <!-- Available Schedule Section -->
            <div id="scheduled-section" class="transition-all duration-300">
                <label for="schedule_id" class="block text-gray-700 font-semibold mb-2">Jadwal Tersedia:</label>
                <select name="schedule_id" id="schedule_id"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                    required disabled>
                    <option value="">Pilih dokter terlebih dahulu...</option>
                </select>
            </div>

            <!-- Preferred Schedule Section -->
            <div id="preferred-section" class="hidden space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="group">
                        <label for="preferred_date" class="block text-gray-700 font-semibold mb-2">Tanggal Pilihan:</label>
                        <input type="date" name="preferred_date" id="preferred_date"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                            min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    </div>
                    <div class="group">
                        <label for="preferred_time" class="block text-gray-700 font-semibold mb-2">Waktu Pilihan:</label>
                        <select name="preferred_time" id="preferred_time"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            <option value="">Pilih waktu...</option>
                            <option value="morning">Pagi (09:00 - 12:00)</option>
                            <option value="afternoon">Siang (13:00 - 17:00)</option>
                            <option value="evening">Malam (18:00 - 20:00)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Reason Section -->
            <div class="group">
                <label for="reason" class="block text-gray-700 font-semibold mb-2">Alasan Kunjungan:</label>
                <textarea name="reason" id="reason" rows="3"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                    required></textarea>
            </div>

            <!-- Symptoms Section -->
            <div class="group">
                <label for="symptoms" class="block text-gray-700 font-semibold mb-2">Gejala yang Dialami:</label>
                <textarea name="symptoms" id="symptoms" rows="3"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"></textarea>
            </div>

            <!-- Notes Section -->
            <div class="group">
                <label for="notes" class="block text-gray-700 font-semibold mb-2">Catatan Tambahan (Opsional):</label>
                <textarea name="notes" id="notes" rows="2"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-8 py-3 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    Kirim Permintaan
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
