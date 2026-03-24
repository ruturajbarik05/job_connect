@extends('layouts.backend')
@section('title', 'User Details')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>User Details</h4>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back</a>
    </div>
    
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center text-white mb-3" style="width: 100px; height: 100px; font-size: 36px;">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    <span class="badge bg-{{ $user->status == 'active' ? 'success' : ($user->status == 'suspended' ? 'warning' : 'danger') }}">
                        {{ ucfirst($user->status) }}
                    </span>
                    <hr>
                    <p><strong>Role:</strong> {{ $user->role->name ?? 'N/A' }}</p>
                    <p><strong>Joined:</strong> {{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            @if($user->company)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Company Info</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Company:</strong> {{ $user->company->name }}</p>
                        <p><strong>Industry:</strong> {{ $user->company->industry ?? 'N/A' }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($user->company->status) }}</p>
                        <p><strong>Verified:</strong> {{ $user->company->is_verified ? 'Yes' : 'No' }}</p>
                    </div>
                </div>
            @endif
            
            @if($user->jobSeekerProfile)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Profile Info</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Phone:</strong> {{ $user->jobSeekerProfile->phone ?? 'N/A' }}</p>
                        <p><strong>Location:</strong> {{ $user->jobSeekerProfile->location ?? 'N/A' }}</p>
                        <p><strong>Experience:</strong> {{ ucfirst($user->jobSeekerProfile->experience_level ?? 'N/A') }}</p>
                    </div>
                </div>
            @endif
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Update Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.status', $user) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <select name="status" class="form-select">
                                    <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="suspended" {{ $user->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    <option value="banned" {{ $user->status == 'banned' ? 'selected' : '' }}>Banned</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
