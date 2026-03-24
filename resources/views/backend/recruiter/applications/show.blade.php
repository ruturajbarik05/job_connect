@extends('layouts.backend')
@section('title', 'Application Details')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Application Details</h4>
        <a href="{{ URL::previous() }}" class="btn btn-secondary">Back</a>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Candidate Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            @if($application->user->avatar)
                                <img src="{{ Storage::url($application->user->avatar) }}" alt="" class="rounded mb-3" width="120" height="120" style="object-fit: cover;">
                            @else
                                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center text-white mb-3" style="width: 120px; height: 120px; font-size: 48px;">
                                    {{ substr($application->user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h4>{{ $application->user->name }}</h4>
                            <p class="text-muted mb-2">{{ $application->user->email }}</p>
                            @if($application->user->jobSeekerProfile)
                                <p class="mb-1"><i class="bi bi-telephone me-2"></i>{{ $application->user->jobSeekerProfile->phone ?? 'N/A' }}</p>
                                <p class="mb-1"><i class="bi bi-geo-alt me-2"></i>{{ $application->user->jobSeekerProfile->location ?? 'N/A' }}</p>
                            @endif
                        </div>
                    </div>
                    
                    @if($application->user->jobSeekerProfile && $application->user->jobSeekerProfile->summary)
                        <hr>
                        <h6>Professional Summary</h6>
                        <p>{{ $application->user->jobSeekerProfile->summary }}</p>
                    @endif
                    
                    @if($application->user->jobSeekerProfile && $application->user->jobSeekerProfile->skills)
                        <hr>
                        <h6>Skills</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($application->user->jobSeekerProfile->skills as $skill)
                                <span class="badge bg-primary">{{ $skill }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            
            @if($application->cover_letter)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Cover Letter</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $application->cover_letter }}</p>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Application Status</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('recruiter.applications.status', $application) }}">
                        @csrf
                        <div class="mb-3">
                            <select name="status" class="form-select">
                                <option value="applied" {{ $application->status == 'applied' ? 'selected' : '' }}>Applied</option>
                                <option value="viewed" {{ $application->status == 'viewed' ? 'selected' : '' }}>Viewed</option>
                                <option value="shortlisted" {{ $application->status == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                <option value="interview" {{ $application->status == 'interview' ? 'selected' : '' }}>Interview</option>
                                <option value="offer" {{ $application->status == 'offer' ? 'selected' : '' }}>Offer</option>
                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3">{{ $application->notes }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Status</button>
                    </form>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Job Details</h5>
                </div>
                <div class="card-body">
                    <p><strong>Position:</strong> {{ $application->job->title }}</p>
                    <p><strong>Applied:</strong> {{ $application->applied_at->format('M d, Y') }}</p>
                    <a href="{{ route('jobs.show', $application->job->slug) }}" target="_blank" class="btn btn-outline-primary btn-sm">View Job</a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Resume</h5>
                </div>
                <div class="card-body">
                    @if($application->user->jobSeekerProfile && $application->user->jobSeekerProfile->resume)
                        <a href="{{ route('recruiter.applications.resume', $application) }}" class="btn btn-success">
                            <i class="bi bi-download me-2"></i>Download Resume
                        </a>
                    @else
                        <p class="text-muted">No resume uploaded.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
