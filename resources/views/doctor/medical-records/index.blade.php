@extends('layouts.doctor')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Rekam Medis</h2>
            <a href="{{ route('doctor.medical-records.create') }}"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Tambah
            </a>
        </div>

        <!-- Search Form -->
        <div class="mb-6 bg-white p-4 rounded-lg shadow">
            <form action="{{ route('doctor.medical-records.index') }}" method="GET" class="flex gap-4">
                <div class="flex-1">
                    <input type="text" name="patient" value="{{ request('patient') }}"
                        class="w-full border rounded px-3 py-2" placeholder="Cari berdasarkan nama pasien...">
                </div>
                <div class="flex-1">
                    <input type="date" name="date" value="{{ request('date') }}"
                        class="w-full border rounded px-3 py-2">
                </div>
                <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Cari
                </button>
            </form>
        </div>

        <!-- Records List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pasien
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Pemeriksaan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($records as $record)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $record->patient->user->username }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $record->treatment_date->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $record->status === 'COMPLETED'
                                        ? 'bg-green-100 text-green-800'
                                        : ($record->status === 'IN_PROGRESS'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : 'bg-red-100 text-red-800') }}">
                                    {{ $record->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('doctor.medical-records.show', $record) }}"
                                    class="text-blue-600 hover:text-blue-900 mr-3">Lihat</a>
                                @if (Auth::user()->doctor->doctor_id === $record->creator_doctor_id)
                                    <a href="{{ route('doctor.medical-records.edit', $record) }}"
                                        class="text-yellow-600 hover:text-yellow-900 mr-3">Ubah</a>
                                    <form action="{{ route('doctor.medical-records.destroy', $record) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus rekam medis ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada rekam medis ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $records->links() }}
        </div>
    </div>
@endsection
