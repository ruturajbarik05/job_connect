@extends('layouts.backend')
@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="mb-0">Total Users</h6>
                    <h2 class="mb-0">{{ $stats['totalUsers'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="mb-0">Active Jobs</h6>
                    <h2 class="mb-0">{{ $stats['activeJobs'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h6 class="mb-0">Pending Companies</h6>
                    <h2 class="mb-0">{{ $stats['pendingCompanies'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="mb-0">Total Applications</h6>
                    <h2 class="mb-0">{{ $stats['totalApplications'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Users</h5>
                </div>
                <div class="card-body p-0">
                    @forelse($recentUsers as $user)
                        <div class="d-flex align-items-center p-3 border-bottom">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 40px; height: 40px;">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <strong>{{ $user->name }}</strong>
                                <br><small class="text-muted">{{ $user->role->name ?? 'N/A' }}</small>
                            </div>
                            <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-center text-muted py-4">No users found.</p>
                    @endforelse
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Jobs</h5>
                </div>
                <div class="card-body p-0">
                    @forelse($recentJobs as $job)
                        <div class="d-flex align-items-center p-3 border-bottom">
                            <div class="flex-grow-1">
                                <strong>{{ $job->title }}</strong>
                                <br><small class="text-muted">{{ $job->company->name ?? 'N/A' }}</small>
                            </div>
                            <span class="badge bg-{{ $job->status == 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($job->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-center text-muted py-4">No jobs found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
