@extends('layouts.app')
@section('title', 'Browse Jobs')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Filter Jobs</h5>
                    <form action="{{ route('jobs.search') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label">Keywords</label>
                            <input type="text" name="search" class="form-control" placeholder="Job title, skills..." value="{{ request('search') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control" placeholder="City or region" value="{{ request('location') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Job Type</label>
                            <select name="job_type" class="form-select">
                                <option value="">All Types</option>
                                <option value="full-time" {{ request('job_type') == 'full-time' ? 'selected' : '' }}>Full Time</option>
                                <option value="part-time" {{ request('job_type') == 'part-time' ? 'selected' : '' }}>Part Time</option>
                                <option value="contract" {{ request('job_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                                <option value="internship" {{ request('job_type') == 'internship' ? 'selected' : '' }}>Internship</option>
                                <option value="freelance" {{ request('job_type') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Work Mode</label>
                            <select name="work_mode" class="form-select">
                                <option value="">All Modes</option>
                                <option value="onsite" {{ request('work_mode') == 'onsite' ? 'selected' : '' }}>Onsite</option>
                                <option value="remote" {{ request('work_mode') == 'remote' ? 'selected' : '' }}>Remote</option>
                                <option value="hybrid" {{ request('work_mode') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>{{ $jobs->total() }} Jobs Found</h4>
                <select class="form-select w-auto" onchange="window.location.href=this.value">
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}">Newest First</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'salary_high']) }}" {{ request('sort') == 'salary_high' ? 'selected' : '' }}>Highest Salary</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'salary_low']) }}" {{ request('sort') == 'salary_low' ? 'selected' : '' }}>Lowest Salary</option>
                </select>
            </div>
            
            @forelse($jobs as $job)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 col-3">
                                @if($job->company && $job->company->logo)
                                    <img src="{{ Storage::url($job->company->logo) }}" alt="{{ $job->company->name }}" class="img-fluid rounded">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                        <i class="bi bi-building text-secondary fs-4"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-7 col-9">
                                <h5 class="mb-1">
                                    <a href="{{ route('jobs.show', $job->slug) }}" class="text-decoration-none text-dark">{{ $job->title }}</a>
                                </h5>
                                <p class="text-muted mb-2">
                                    {{ $job->company->name ?? 'Company' }}
                                    @if($job->company && $job->company->is_verified)
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @endif
                                </p>
                                <div class="mb-2">
                                    <span class="badge bg-light text-dark me-2"><i class="bi bi-geo-alt me-1"></i>{{ $job->location ?? 'Remote' }}</span>
                                    <span class="badge bg-light text-dark me-2"><i class="bi bi-clock me-1"></i>{{ ucfirst(str_replace('-', ' ', $job->job_type)) }}</span>
                                    <span class="badge bg-light text-dark"><i class="bi bi-laptop me-1"></i>{{ ucfirst($job->work_mode) }}</span>
                                </div>
                                @if($job->skills_required)
                                    <div class="small">
                                        @foreach(array_slice($job->skills_required, 0, 3) as $skill)
                                            <span class="badge bg-secondary me-1">{{ $skill }}</span>
                                        @endforeach
                                        @if(count($job->skills_required) > 3)
                                            <span class="text-muted">+{{ count($job->skills_required) - 3 }} more</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-3 text-md-end mt-3 mt-md-0">
                                <h6 class="text-primary mb-2">{{ $job->salary_range }}</h6>
                                <small class="text-muted d-block mb-2">{{ $job->days_ago }}</small>
                                <a href="{{ route('jobs.show', $job->slug) }}" class="btn btn-outline-primary btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-search display-1 text-secondary"></i>
                    <h4 class="mt-3">No jobs found</h4>
                    <p class="text-muted">Try adjusting your filters or search terms.</p>
                </div>
            @endforelse
            
            <div class="d-flex justify-content-center">
                {{ $jobs->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
