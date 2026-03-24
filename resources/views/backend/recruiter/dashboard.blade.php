@extends('layouts.backend')
@section('title', 'Recruiter Dashboard')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif
    
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="mb-0">Total Jobs</h6>
                    <h2 class="mb-0">{{ $stats['totalJobs'] }}</h2>
                </div>
                <a href="{{ route('recruiter.jobs.index') }}" class="card-footer text-white text-decoration-none">
                    View All <i class="bi bi-arrow-right ms-1"></i>
                </a>
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
                    <h6 class="mb-0">Total Applications</h6>
                    <h2 class="mb-0">{{ $stats['totalApplications'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="mb-0">New Applications</h6>
                    <h2 class="mb-0">{{ $stats['newApplications'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Applications</h5>
                </div>
                <div class="card-body p-0">
                    @forelse($recentApplications as $application)
                        <div class="d-flex align-items-center p-3 border-bottom">
                            <div class="flex-shrink-0 me-3">
                                @if($application->user->avatar)
                                    <img src="{{ Storage::url($application->user->avatar) }}" alt="" width="50" class="rounded-circle">
                                @else
                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 50px; height: 50px;">
                                        {{ substr($application->user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $application->user->name }}</h6>
                                <small class="text-muted">{{ $application->job->title }}</small>
                            </div>
                            <span class="badge bg-{{ $application->status == 'applied' ? 'primary' : ($application->status == 'shortlisted' ? 'success' : 'secondary') }}">
                                {{ ucfirst($application->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-center text-muted py-4">No applications yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Company Status</h5>
                </div>
                <div class="card-body">
                    @if($company)
                        <div class="text-center mb-3">
                            @if($company->status === 'approved')
                                <span class="badge bg-success fs-6"><i class="bi bi-check-circle me-1"></i>Approved</span>
                            @elseif($company->status === 'pending')
                                <span class="badge bg-warning fs-6"><i class="bi bi-clock me-1"></i>Pending Approval</span>
                            @else
                                <span class="badge bg-danger fs-6"><i class="bi bi-x-circle me-1"></i>Rejected</span>
                            @endif
                        </div>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <strong>Company Name:</strong> {{ $company->name }}
                            </li>
                            <li class="mb-2">
                                <strong>Industry:</strong> {{ $company->industry ?? 'Not specified' }}
                            </li>
                            <li>
                                <strong>Verified:</strong> 
                                @if($company->is_verified)
                                    <span class="text-success"><i class="bi bi-check-circle"></i> Yes</span>
                                @else
                                    <span class="text-muted">No</span>
                                @endif
                            </li>
                        </ul>
                        @if($company->status !== 'approved')
                            <p class="text-muted small">Your company profile is being reviewed. You'll be able to post jobs once approved.</p>
                        @endif
                    @else
                        <p class="text-muted">Please complete your company profile.</p>
                        <a href="{{ route('recruiter.company.profile') }}" class="btn btn-primary btn-sm">Create Profile</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
