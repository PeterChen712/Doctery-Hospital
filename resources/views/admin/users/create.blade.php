@extends('layouts.admin')

@section('header')
    <h2 class="text-xl font-semibold">Create New User</h2>
@endsection

@section('content')

    <x-app-layout>
        <x-slot name="header">
            <h2 class="text-xl font-semibold">Create New User</h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form method="POST" action="{{ route('admin.users.store') }}">
                            @csrf

                            <div class="mb-4">
                                <label class="block mb-2">Username</label>
                                <input type="text" name="username" value="{{ old('username') }}"
                                    class="rounded-md shadow-sm border-gray-300 w-full">
                                @error('username')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block mb-2">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="rounded-md shadow-sm border-gray-300 w-full">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block mb-2">Password</label>
                                <input type="password" name="password" class="rounded-md shadow-sm border-gray-300 w-full">
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block mb-2">Confirm Password</label>
                                <input type="password" name="password_confirmation"
                                    class="rounded-md shadow-sm border-gray-300 w-full">
                            </div>

                            <div class="mb-4">
                                <label class="block mb-2">Role</label>
                                <select name="role" class="rounded-md shadow-sm border-gray-300 w-full">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block mb-2">Phone Number</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number') }}"
                                    class="rounded-md shadow-sm border-gray-300 w-full">
                                @error('phone_number')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block mb-2">Address</label>
                                <textarea name="address" class="rounded-md shadow-sm border-gray-300 w-full">{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                                    Create User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>

@endsection