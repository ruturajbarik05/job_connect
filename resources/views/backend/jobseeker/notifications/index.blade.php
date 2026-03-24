@extends('layouts.backend')
@section('title', 'Notifications')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Notifications</h4>
        @if($notifications->count() > 0)
            <form action="{{ route('jobseeker.notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-primary btn-sm">Mark All as Read</button>
            </form>
        @endif
    </div>
    
    <div class="card">
        <div class="card-body p-0">
            @forelse($notifications as $notification)
                <a href="{{ $notification->link ? route('jobseeker.notifications.read', $notification->id) : '#' }}" 
                   class="d-block p-3 border-bottom text-decoration-none {{ $notification->is_read ? 'bg-light' : 'bg-white' }}">
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <i class="bi bi-bell-fill text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $notification->title }}</h6>
                            <p class="mb-0 text-muted small">{{ $notification->message }}</p>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                        @if(!$notification->is_read)
                            <span class="badge bg-danger">New</span>
                        @endif
                    </div>
                </a>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-bell display-1 text-secondary"></i>
                    <p class="text-muted mt-3">No notifications</p>
                </div>
            @endforelse
        </div>
    </div>
    
    @if($notifications->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
