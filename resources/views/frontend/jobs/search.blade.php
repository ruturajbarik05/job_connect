@extends('layouts.app')
@section('title', 'Search Results')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Search Results ({{ $jobs->total() }})</h2>
    
    <div class="row">
        <div class="col-lg-3">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Filter Jobs</h5>
                    <form action="{{ route('jobs.search') }}" method="GET">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if(request('location'))
                            <input type="hidden" name="location" value="{{ request('location') }}">
                        @endif
                        
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
            @forelse($jobs as $job)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                @if($job->company && $job->company->logo)
                                    <img src="{{ Storage::url($job->company->logo) }}" alt="" class="img-fluid rounded">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                        <i class="bi bi-building text-secondary"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-7">
                                <h5 class="mb-1">
                                    <a href="{{ route('jobs.show', $job->slug) }}" class="text-decoration-none text-dark">{{ $job->title }}</a>
                                </h5>
                                <p class="text-muted">{{ $job->company->name ?? 'Company' }}</p>
                                <div class="mb-2">
                                    <span class="badge bg-light text-dark me-2"><i class="bi bi-geo-alt me-1"></i>{{ $job->location ?? 'Remote' }}</span>
                                    <span class="badge bg-light text-dark"><i class="bi bi-clock me-1"></i>{{ ucfirst($job->job_type) }}</span>
                                </div>
                            </div>
                            <div class="col-md-3 text-md-end">
                                <p class="text-primary fw-bold mb-2">{{ $job->salary_range }}</p>
                                <a href="{{ route('jobs.show', $job->slug) }}" class="btn btn-outline-primary btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info text-center py-5">
                    <i class="bi bi-search display-4 text-secondary"></i>
                    <h4 class="mt-3">No jobs found</h4>
                    <p class="text-muted">Try adjusting your search criteria.</p>
                </div>
            @endforelse
            
            <div class="d-flex justify-content-center">
                {{ $jobs->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
