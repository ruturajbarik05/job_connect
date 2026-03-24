@extends('layouts.backend')
@section('title', 'Notifications')

@section('content')
<div class="container-fluid py-4">
    <h4 class="mb-4">Notifications</h4>
    
    <div class="card">
        <div class="card-body p-0">
            @forelse(auth()->user()->notifications()->latest()->take(20)->get() as $notification)
                <div class="p-3 border-bottom">
                    <strong>{{ $notification->title }}</strong>
                    <p class="mb-0 text-muted">{{ $notification->message }}</p>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
            @empty
                <p class="text-center py-5 text-muted">No notifications.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
