@extends('layouts.admin')

@section('header')
    <div class="flex justify-between">
        <h2 class="text-xl font-semibold">User Management</h2>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Add User</a>
    </div>
@endsection

@section('content')

    <x-app-layout>
        <x-slot name="header">
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold">User Management</h2>
                <a href="{{ route('admin.users.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Add User</a>
            </div>
        </x-slot>

        {{-- Filters --}}
        <div class="p-6 bg-white rounded-lg mb-4">
            <form method="GET" class="flex gap-4">
                <select name="role" class="rounded-md">
                    <option value="">All Roles</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
                <input type="date" name="date" value="{{ request('date') }}" class="rounded-md">
                <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded">Filter</button>
            </form>
        </div>

        {{-- Users Table --}}
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">Username</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Role</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($users as $user)
                        <tr>
                            <td class="px-6 py-4">{{ $user->username }}</td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">{{ $user->roles->first()->name ?? 'No Role' }}</td>
                            <td class="px-6 py-4 flex gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600">Edit</a>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4">
                {{ $users->links() }}
            </div>
        </div>
    </x-app-layout>

@endsection
