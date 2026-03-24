@extends('layouts.backend')
@section('title', 'Job Details')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Job Details</h4>
        <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Jobs
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-start mb-3">
                        @if($job->company && $job->company->logo)
                            <img src="{{ Storage::url($job->company->logo) }}" alt="{{ $job->company->name }}" class="rounded me-3" width="60" height="60" style="object-fit: cover;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-building text-secondary"></i>
                            </div>
                        @endif
                        <div>
                            <h5 class="mb-1">{{ $job->title }}</h5>
                            <p class="text-muted mb-0">{{ $job->company->name ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <span class="badge bg-{{ $job->status == 'active' ? 'success' : ($job->status == 'pending' ? 'warning' : 'secondary') }} me-2">
                            {{ ucfirst($job->status) }}
                        </span>
                        @if($job->is_featured)
                            <span class="badge bg-warning text-dark me-2">Featured</span>
                        @endif
                        @if($job->is_verified)
                            <span class="badge bg-info me-2">Verified</span>
                        @endif
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Location:</strong> {{ $job->location ?? 'Not specified' }}</p>
                            <p class="mb-1"><strong>Job Type:</strong> {{ ucfirst(str_replace('-', ' ', $job->job_type ?? 'N/A')) }}</p>
                            <p class="mb-1"><strong>Work Mode:</strong> {{ ucfirst($job->work_mode ?? 'N/A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Salary:</strong> {{ $job->salary_range }}</p>
                            <p class="mb-1"><strong>Experience:</strong> {{ ucfirst($job->experience_level ?? 'N/A') }}</p>
                            <p class="mb-1"><strong>Vacancies:</strong> {{ $job->vacancies ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @if($job->application_deadline)
                        <p class="mb-3"><strong>Deadline:</strong> {{ $job->application_deadline->format('M d, Y') }}</p>
                    @endif

                    <hr>
                    <h6>Description</h6>
                    <div class="mb-3">{!! nl2br(e($job->description)) !!}</div>

                    @if($job->requirements)
                        <h6>Requirements</h6>
                        <div class="mb-3">{!! nl2br(e($job->requirements)) !!}</div>
                    @endif

                    @if($job->responsibilities)
                        <h6>Responsibilities</h6>
                        <div class="mb-3">{!! nl2br(e($job->responsibilities)) !!}</div>
                    @endif

                    @if($job->benefits)
                        <h6>Benefits</h6>
                        <div class="mb-3">{!! nl2br(e($job->benefits)) !!}</div>
                    @endif

                    @if($job->skills_required && count($job->skills_required))
                        <h6>Skills Required</h6>
                        <div class="mb-3">
                            @foreach($job->skills_required as $skill)
                                <span class="badge bg-light text-dark me-1 mb-1">{{ $skill }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header"><strong>Job Info</strong></div>
                <div class="card-body">
                    <p class="mb-2"><strong>Posted by:</strong> {{ $job->user->name ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>Category:</strong> {{ $job->category->name ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>Views:</strong> {{ $job->views }}</p>
                    <p class="mb-2"><strong>Applications:</strong> {{ $job->applications->count() }}</p>
                    <p class="mb-2"><strong>Created:</strong> {{ $job->created_at->format('M d, Y') }}</p>

                    <hr>
                    <form action="{{ route('admin.jobs.status', $job) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label"><strong>Update Status</strong></label>
                            <select name="status" class="form-select">
                                <option value="active" {{ $job->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="pending" {{ $job->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="closed" {{ $job->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                <option value="draft" {{ $job->status == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Status</button>
                    </form>
                </div>
            </div>

            @if($job->applications->count() > 0)
                <div class="card">
                    <div class="card-header"><strong>Recent Applicants</strong></div>
                    <div class="list-group list-group-flush">
                        @foreach($job->applications->take(10) as $application)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $application->user->name }}</strong>
                                        <br><small class="text-muted">{{ $application->created_at->format('M d, Y') }}</small>
                                    </div>
                                    <span class="badge bg-{{ $application->status == 'applied' ? 'info' : ($application->status == 'shortlisted' ? 'success' : 'secondary') }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
