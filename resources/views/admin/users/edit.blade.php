@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-xl font-semibold mb-6">Edit User</h2>
                
                <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block mb-2">Username</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}"
                            class="rounded-md shadow-sm border-gray-300 w-full">
                        @error('username')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="rounded-md shadow-sm border-gray-300 w-full">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2">New Password</label>
                        <input type="password" name="password" class="rounded-md shadow-sm border-gray-300 w-full">
                        <p class="text-gray-500 text-sm">Leave blank to keep current password</p>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2">Confirm New Password</label>
                        <input type="password" name="password_confirmation"
                            class="rounded-md shadow-sm border-gray-300 w-full">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2">Role</label>
                        <select name="role" class="rounded-md shadow-sm border-gray-300 w-full">
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}"
                                    {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2">Phone Number</label>
                        <input type="text" name="phone_number"
                            value="{{ old('phone_number', $user->phone_number) }}"
                            class="rounded-md shadow-sm border-gray-300 w-full">
                        @error('phone_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2">Address</label>
                        <textarea name="address" class="rounded-md shadow-sm border-gray-300 w-full">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2">Profile Image</label>
                        @if ($user->profile_image)
                            <div class="mb-2">
                                <img src="{{ Storage::url($user->profile_image) }}" class="w-32">
                            </div>
                        @endif
                        <input type="file" name="profile_image">
                        @error('profile_image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection