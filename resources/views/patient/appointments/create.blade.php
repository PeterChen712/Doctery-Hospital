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
                                Dr. {{ $doctor->user->username }}
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

    @push('scripts')
        <script>
            document.getElementById('doctor_id').addEventListener('change', function() {
                const doctorId = this.value;
                const scheduleSelect = document.getElementById('schedule_id');

                if (doctorId) {
                    fetch(`/patient/doctors/${doctorId}/schedules`)
                        .then(response => response.json())
                        .then(data => {
                            scheduleSelect.innerHTML = '<option value="">Select a schedule</option>';
                            data.schedules.forEach(schedule => {
                                scheduleSelect.innerHTML += `
                        <option value="${schedule.schedule_id}">
                            ${schedule.day} - ${schedule.start_time}
                        </option>`;
                            });
                            scheduleSelect.disabled = false;
                        });
                } else {
                    scheduleSelect.innerHTML = '<option value="">Select doctor first</option>';
                    scheduleSelect.disabled = true;
                }
            });
        </script>
    @endpush
@endsection