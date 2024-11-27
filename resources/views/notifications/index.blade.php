{{-- resources/views/notifications/index.blade.php --}}
@extends('layouts.patient')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Notifications</h1>
        
        @if($notifications->count() > 0)
            <form action="{{ route('patient.notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="text-blue-500 hover:text-blue-700">
                    Mark all as read
                </button>
            </form>
        @endif
    </div>

    <div class="space-y-4">
        @forelse($notifications as $notification)
            <div class="bg-white rounded-lg shadow p-4 {{ $notification->read_at ? 'opacity-50' : '' }}">
                <div class="flex justify-between">
                    <h3 class="font-semibold">{{ $notification->title }}</h3>
                    @if(!$notification->read_at)
                        <form action="{{ route('patient.notifications.markAsRead', $notification) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-sm text-blue-500 hover:text-blue-700">
                                Mark as read
                            </button>
                        </form>
                    @endif
                </div>
                <p class="text-gray-600 mt-2">
                    {{ json_decode($notification->data)->message ?? '' }}
                </p>
                <span class="text-sm text-gray-500">
                    {{ $notification->created_at->diffForHumans() }}
                </span>
            </div>
        @empty
            <p class="text-gray-500 text-center py-4">No notifications found.</p>
        @endforelse
    </div>
</div>
@endsection