@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Buat Pengguna Baru</h2>
        
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-8">
                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                    @csrf

                    <div class="group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pengguna</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" name="username" value="{{ old('username') }}"
                                class="pl-10 w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200">
                        </div>
                        @error('username')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="pl-10 w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" name="password"
                                    class="pl-10 w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200">
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Kata Sandi</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" name="password_confirmation"
                                    class="pl-10 w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200">
                            </div>
                        </div>
                    </div>

                    <div class="group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Peran</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                <i class="fas fa-user-tag"></i>
                            </span>
                            <select name="role" 
                                class="pl-10 w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('role')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                <i class="fas fa-phone"></i>
                            </span>
                            <input type="text" name="phone_number" value="{{ old('phone_number') }}"
                                class="pl-10 w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200">
                        </div>
                        @error('phone_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                        <div class="relative">
                            <span class="absolute top-3 left-0 pl-3 flex items-center text-gray-500">
                                <i class="fas fa-home"></i>
                            </span>
                            <textarea name="address" rows="3"
                                class="pl-10 w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200">{{ old('address') }}</textarea>
                        </div>
                        @error('address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end pt-6">
                        <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium rounded-lg 
                            hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 
                            transform transition hover:-translate-y-0.5">
                            Buat Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection