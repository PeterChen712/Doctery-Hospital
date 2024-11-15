@extends('layouts.patient')

@section('content')
<div class="container mx-auto px-4">
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Notifications</h2>
                @if($notifications->where('is_read', false)->count() > 0)
                    <form action="{{ route('patient.notifications.mark-all-read') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            Mark all as read
                        </button>
                    </form>
                @endif
            </div>

            <div class="space-y-4">
                @forelse($notifications as $notification)
                    <div class="p-4 {{ $notification->is_read ? 'bg-gray-50' : 'bg-blue-50' }} rounded-lg transition-colors duration-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $notification->title }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $notification->message }}</p>
                            </div>
                            <span class="text-xs text-gray-400 dark:text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        @if(!$notification->is_read)
                            <form action="{{ route('patient.notifications.mark-read', $notification) }}" method="POST" class="mt-4">
                                @csrf
                                <button type="submit" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    Mark as read
                                </button>
                            </form>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400">No new notifications.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection