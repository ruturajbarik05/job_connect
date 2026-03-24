@extends('layouts.backend')
@section('title', 'Application Details')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Application Details</h4>
        <a href="{{ route('jobseeker.applications.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Applications
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="mb-3">{{ $application->job->title ?? 'Job Not Available' }}</h5>
                    
                    @if($application->job && $application->job->company)
                        <p class="text-muted mb-3">
                            <i class="bi bi-building me-1"></i>{{ $application->job->company->name }}
                            <span class="mx-2">|</span>
                            <i class="bi bi-geo-alt me-1"></i>{{ $application->job->location ?? 'Remote' }}
                        </p>
                    @endif

                    @if($application->job)
                        <div class="mb-3">
                            <span class="badge bg-light text-dark me-1">{{ ucfirst(str_replace('-', ' ', $application->job->job_type ?? '')) }}</span>
                            <span class="badge bg-light text-dark me-1">{{ ucfirst($application->job->work_mode ?? '') }}</span>
                            <span class="badge bg-light text-dark">{{ $application->job->salary_range }}</span>
                        </div>

                        @if($application->job->description)
                            <h6>Job Description</h6>
                            <div class="mb-3">{!! nl2br(e($application->job->description)) !!}</div>
                        @endif
                    @endif

                    @if($application->cover_letter)
                        <hr>
                        <h6>Your Cover Letter</h6>
                        <p>{{ $application->cover_letter }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header"><strong>Application Status</strong></div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <span class="badge bg-{{ 
                            $application->status == 'applied' ? 'info' : 
                            ($application->status == 'viewed' ? 'primary' : 
                            ($application->status == 'shortlisted' ? 'success' : 
                            ($application->status == 'interview' ? 'warning' : 
                            ($application->status == 'offer' ? 'success' : 
                            ($application->status == 'rejected' ? 'danger' : 'secondary'))))) 
                        }} fs-6 px-3 py-2">
                            {{ ucfirst($application->status) }}
                        </span>
                    </div>

                    <p class="mb-2"><strong>Applied:</strong> {{ $application->applied_at ? $application->applied_at->format('M d, Y') : $application->created_at->format('M d, Y') }}</p>
                    
                    @if($application->reviewed_at)
                        <p class="mb-2"><strong>Reviewed:</strong> {{ $application->reviewed_at->format('M d, Y') }}</p>
                    @endif

                    @if($application->notes)
                        <hr>
                        <h6>Recruiter Notes</h6>
                        <p class="text-muted">{{ $application->notes }}</p>
                    @endif

                    @if(!in_array($application->status, ['withdrawn', 'rejected']))
                        <hr>
                        <form action="{{ route('jobseeker.applications.withdraw', $application) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Are you sure you want to withdraw this application?')">
                                Withdraw Application
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
