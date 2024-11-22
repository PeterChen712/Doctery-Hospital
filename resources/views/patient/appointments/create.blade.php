@extends('layouts.patient')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-2xl font-bold mb-6">Schedule New Appointment</h1>

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('patient.appointments.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700">Select Doctor</label>
                    <select name="doctor_id" id="doctor_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        required>
                        <option value="">Choose a doctor</option>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->doctor_id }}">
                                {{ $doctor->user->username }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Available Schedule</label>
                    <select name="schedule_id" id="schedule_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required disabled>
                        <option value="">Select doctor first</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Reason for Visit</label>
                    <textarea name="reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('reason') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Symptoms (Optional)</label>
                    <textarea name="symptoms" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('symptoms') }}</textarea>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('patient.appointments.index') }}"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Schedule Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const doctorSelect = document.getElementById('doctor_id');
            const scheduleSelect = document.getElementById('schedule_id');

            doctorSelect.addEventListener('change', async function() {
                const doctorId = this.value;
                console.log('Selected doctor ID:', doctorId); // Debug log

                scheduleSelect.disabled = true;
                scheduleSelect.innerHTML = '<option value="">Loading schedules...</option>';

                if (!doctorId) return;

                try {
                    const url = `/patient/doctor-schedules/${doctorId}`;
                    console.log('Fetching URL:', url);

                    const response = await fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    });

                    console.log('Response status:', response.status);
                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    scheduleSelect.innerHTML = '<option value="">Select a schedule</option>';
                    if (data && data.length > 0) {
                        data.forEach(schedule => {
                            const option = document.createElement('option');
                            option.value = schedule.schedule_id;
                            const date = new Date(schedule.schedule_date).toLocaleDateString();
                            option.textContent =
                                `${date} (${schedule.start_time} - ${schedule.end_time})`;
                            scheduleSelect.appendChild(option);
                        });
                        scheduleSelect.disabled = false;
                    } else {
                        scheduleSelect.innerHTML = '<option value="">No available schedules</option>';
                    }
                } catch (error) {
                    console.error('Detailed error:', error); // Debug log
                    scheduleSelect.innerHTML =
                        '<option value="">Failed to load schedules. Please try again.</option>';
                }
            });
        });
    </script>
@endsection
