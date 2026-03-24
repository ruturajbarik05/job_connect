@extends('layouts.app')
@section('title', $job->title)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-start mb-4">
                        <div class="flex-shrink-0 me-3">
                            @if($job->company && $job->company->logo)
                                <img src="{{ Storage::url($job->company->logo) }}" alt="{{ $job->company->name }}" class="rounded" width="80" height="80" style="object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="bi bi-building text-secondary fs-2"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h2 class="mb-1">{{ $job->title }}</h2>
                                    <p class="mb-0">
                                        <a href="{{ route('companies.show', $job->company->slug ?? '#') }}" class="text-decoration-none">
                                            {{ $job->company->name ?? 'Company' }}
                                        </a>
                                        @if($job->company && $job->company->is_verified)
                                            <span class="badge bg-success ms-2"><i class="bi bi-check-circle me-1"></i>Verified</span>
                                        @endif
                                    </p>
                                </div>
                                @if(auth()->check() && auth()->user()->isJobSeeker())
                                    <div>
                                        @if($isSaved)
                                            <form action="{{ route('jobs.save', $job) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-warning"><i class="bi bi-bookmark-fill"></i> Saved</button>
                                            </form>
                                        @else
                                            <form action="{{ route('jobs.save', $job) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-warning"><i class="bi bi-bookmark"></i> Save</button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4 col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-geo-alt text-primary me-2 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Location</small>
                                    <strong>{{ $job->location ?? 'Remote' }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-clock text-primary me-2 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Job Type</small>
                                    <strong>{{ ucfirst(str_replace('-', ' ', $job->job_type)) }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-laptop text-primary me-2 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Work Mode</small>
                                    <strong>{{ ucfirst($job->work_mode) }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-currency-dollar text-primary me-2 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Salary Range</small>
                                    <strong>{{ $job->salary_range }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-briefcase text-primary me-2 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Experience</small>
                                    <strong>{{ ucfirst($job->experience_level ?? 'Any') }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-people text-primary me-2 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Vacancies</small>
                                    <strong>{{ $job->vacancies }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($job->skills_required)
                        <div class="mb-4">
                            <h5>Required Skills</h5>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($job->skills_required as $skill)
                                    <span class="badge bg-light text-dark p-2">{{ $skill }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="mb-3">Job Description</h4>
                    <div class="mb-4">{!! nl2br(e($job->description)) !!}</div>
                    
                    @if($job->requirements)
                        <h5 class="mb-3">Requirements</h5>
                        <div class="mb-4">{!! nl2br(e($job->requirements)) !!}</div>
                    @endif
                    
                    @if($job->responsibilities)
                        <h5 class="mb-3">Responsibilities</h5>
                        <div class="mb-4">{!! nl2br(e($job->responsibilities)) !!}</div>
                    @endif
                    
                    @if($job->benefits)
                        <h5 class="mb-3">Benefits</h5>
                        <div>{!! nl2br(e($job->benefits)) !!}</div>
                    @endif
                </div>
            </div>
            
            @if(count($relatedJobs) > 0)
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">Similar Jobs</h5>
                        <div class="row">
                            @foreach($relatedJobs as $relatedJob)
                                <div class="col-md-6 mb-3">
                                    <a href="{{ route('jobs.show', $relatedJob->slug) }}" class="text-decoration-none">
                                        <div class="d-flex align-items-center p-2 border rounded">
                                            <div class="me-3">
                                                @if($relatedJob->company && $relatedJob->company->logo)
                                                    <img src="{{ Storage::url($relatedJob->company->logo) }}" alt="" width="40" height="40" class="rounded">
                                                @else
                                                    <i class="bi bi-building text-secondary fs-4"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-dark">{{ $relatedJob->title }}</h6>
                                                <small class="text-muted">{{ $relatedJob->location ?? 'Remote' }}</small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4 sticky-top" style="top: 80px;">
                <div class="card-body">
                    @auth
                        @if(auth()->user()->isJobSeeker())
                            @if($hasApplied)
                                <div class="alert alert-info mb-3">
                                    <i class="bi bi-check-circle me-2"></i>You have already applied for this job.
                                </div>
                            @else
                                <form action="{{ route('jobs.apply', $job) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100 btn-lg mb-3">
                                        Apply Now <i class="bi bi-send ms-2"></i>
                                    </button>
                                </form>
                            @endif
                        @elseif(auth()->user()->isRecruiter())
                            <div class="alert alert-secondary mb-3">
                                Recruiters cannot apply for jobs.
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}?redirect={{ urlencode(route('jobs.show', $job->slug)) }}" class="btn btn-primary w-100 btn-lg mb-3">
                            Login to Apply
                        </a>
                        <p class="text-center text-muted mb-0">
                            Don't have an account? <a href="{{ route('register') }}">Register</a>
                        </p>
                    @endauth
                    
                    <hr>
                    
                    <div class="mb-3">
                        <h6><i class="bi bi-building me-2"></i>About Company</h6>
                        @if($job->company)
                            <p class="mb-1"><strong>{{ $job->company->name }}</strong></p>
                            @if($job->company->industry)
                                <small class="text-muted">{{ $job->company->industry }}</small>
                            @endif
                            @if($job->company->website)
                                <br><a href="{{ $job->company->website }}" target="_blank">{{ Str::limit($job->company->website, 30) }}</a>
                            @endif
                        @else
                            <p class="text-muted">Company information not available.</p>
                        @endif
                    </div>
                    
                    @if($job->application_deadline)
                        <div class="mb-3">
                            <h6><i class="bi bi-calendar me-2"></i>Application Deadline</h6>
                            <p class="mb-0">{{ $job->application_deadline->format('M d, Y') }}</p>
                        </div>
                    @endif
                    
                    <div>
                        <h6><i class="bi bi-share me-2"></i>Share this job</h6>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-outline-secondary btn-sm"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="btn btn-outline-secondary btn-sm"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="btn btn-outline-secondary btn-sm"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
