{{-- resources/views/notifications/index.blade.php --}}
@extends('layouts.patient')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Notifikasi Saya</h1>
    </div>
    
  
    <!-- Red Divider -->
    <div class="h-1 bg-red-500 my-4 rounded-full"></div>

    <!-- Notifications Grid -->
    <div class="grid gap-4">
        @forelse ($notifications as $notification)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-l-4 
                        {{ $notification->read_at ? 'border-gray-300' : 'border-blue-500' }}">
                <div class="flex justify-between items-start">
                    <div>
                        @php
                            $data = is_array($notification->data) 
                                ? $notification->data 
                                : json_decode($notification->data, true);
                        @endphp
                        
                        <h3 class="font-semibold text-lg text-gray-900">
                            {{ $data['title'] ?? 'Notifikasi' }}
                        </h3>
                        <p class="text-gray-600 mt-1">
                            {{ $data['message'] ?? '' }}
                        </p>
                        <span class="text-sm text-gray-500 mt-2 block">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </div>
                    
                    @unless($notification->read_at)
                        <span class="px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                            Belum dibaca
                        </span>
                    @endunless
                </div>
                
                @unless($notification->read_at)
                    <div class="mt-4 flex gap-2">
                        <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                class="text-blue-500 hover:text-blue-700 font-medium">
                                Tandai sudah dibaca
                            </button>
                        </form>
                    </div>
                @endunless
            </div>
        @empty
            <div class="text-gray-500 text-center py-8 bg-white rounded-lg shadow">
                Tidak ada notifikasi.
            </div>
        @endforelse
    </div>

    @if($notifications->isNotEmpty())
        <div class="mt-6 flex justify-end">
            <form method="POST" action="{{ route('notifications.markAllAsRead') }}" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" 
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                    Tandai semua sudah dibaca
                </button>
            </form>
        </div>
    @endif

    <!-- Pagination -->
    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
</div>
@endsection