@extends('layouts.backend')
@section('title', 'Job Seeker Dashboard')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Applied Jobs</h6>
                            <h2 class="mb-0">{{ $stats['appliedJobs'] }}</h2>
                        </div>
                        <i class="bi bi-briefcase fs-1 opacity-50"></i>
                    </div>
                </div>
                <a href="{{ route('jobseeker.applications.index') }}" class="card-footer text-white text-decoration-none">
                    View All <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Saved Jobs</h6>
                            <h2 class="mb-0">{{ $stats['savedJobs'] }}</h2>
                        </div>
                        <i class="bi bi-bookmark fs-1 opacity-50"></i>
                    </div>
                </div>
                <a href="{{ route('jobseeker.saved-jobs.index') }}" class="card-footer text-white text-decoration-none">
                    View All <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Interviews</h6>
                            <h2 class="mb-0">{{ $stats['interviews'] }}</h2>
                        </div>
                        <i class="bi bi-calendar-check fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Notifications</h6>
                            <h2 class="mb-0">{{ $notifications->count() }}</h2>
                        </div>
                        <i class="bi bi-bell fs-1 opacity-50"></i>
                    </div>
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
                <div class="card-body">
                    @forelse($recentApplications as $application)
                        <div class="d-flex align-items-center p-3 border-bottom">
                            <div class="flex-shrink-0 me-3">
                                @if($application->job->company && $application->job->company->logo)
                                    <img src="{{ Storage::url($application->job->company->logo) }}" alt="" width="50" class="rounded">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="bi bi-building"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $application->job->title }}</h6>
                                <small class="text-muted">{{ $application->job->company->name ?? 'Company' }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $application->status == 'shortlisted' ? 'success' : ($application->status == 'rejected' ? 'danger' : 'primary') }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                                <br>
                                <small class="text-muted">{{ $application->applied_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted py-4">No applications yet. Start applying for jobs!</p>
                    @endforelse
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Saved Jobs</h5>
                </div>
                <div class="card-body p-0">
                    @forelse($savedJobs as $job)
                        <a href="{{ route('jobs.show', $job->slug) }}" class="d-block p-3 border-bottom text-decoration-none text-dark">
                            <h6 class="mb-1">{{ $job->title }}</h6>
                            <small class="text-muted">{{ $job->company->name ?? 'Company' }}</small>
                            <br>
                            <span class="text-primary small">{{ $job->salary_range }}</span>
                        </a>
                    @empty
                        <p class="text-center text-muted py-4">No saved jobs yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
