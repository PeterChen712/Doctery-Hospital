{{-- resources/views/notifications/index.blade.php --}}
@extends('layouts.patient')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold mb-4">Notifications</h2>
    
    <div class="space-y-4">
        @forelse ($notifications as $notification)
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-semibold">
                            {{-- Check if data is already an array --}}
                            @php
                                $data = is_array($notification->data) 
                                    ? $notification->data 
                                    : json_decode($notification->data, true);
                            @endphp
                            
                            {{ $data['title'] ?? 'Notification' }}
                        </h3>
                        <p class="text-gray-600">
                            {{ $data['message'] ?? '' }}
                        </p>
                        <span class="text-sm text-gray-500">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </div>
                    
                    @unless($notification->read_at)
                        <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                class="text-blue-600 hover:text-blue-800">
                                Mark as read
                            </button>
                        </form>
                    @endunless
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500">
                No notifications found
            </div>
        @endforelse
    </div>

    @if($notifications->isNotEmpty())
        <div class="mt-4">
            <form method="POST" action="{{ route('notifications.markAllAsRead') }}" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" 
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Mark all as read
                </button>
            </form>
        </div>
    @endif
</div>
@endsection