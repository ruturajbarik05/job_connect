@extends('layouts.app')
@section('title', 'Find Your Dream Job')

@section('content')
<section class="hero bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4">Find Your Dream Job Today</h1>
                <p class="lead mb-4">Discover thousands of job opportunities and take the next step in your career.</p>
                <form action="{{ route('jobs.search') }}" method="GET" class="row g-2 justify-content-center">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control form-control-lg" placeholder="Search jobs, keywords, companies...">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="location" class="form-control form-control-lg" placeholder="Location">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-light btn-lg w-100">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <h3>Featured Jobs</h3>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary">View All Jobs</a>
            </div>
        </div>
        <div class="row g-4">
            @forelse($featuredJobs as $job)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 shadow-sm job-card">
                        @if($job->is_featured)
                            <div class="card-header bg-warning text-dark">
                                <small><i class="bi bi-star-fill me-1"></i>Featured</small>
                            </div>
                        @endif
                        <div class="card-body">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0 me-3">
                                    @if($job->company && $job->company->logo)
                                        <img src="{{ Storage::url($job->company->logo) }}" alt="{{ $job->company->name }}" class="rounded" width="50" height="50" style="object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="bi bi-building text-secondary"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">
                                        <a href="{{ route('jobs.show', $job->slug) }}" class="text-decoration-none text-dark">{{ $job->title }}</a>
                                    </h5>
                                    <small class="text-muted">{{ $job->company->name ?? 'Company' }}</small>
                                    @if($job->company && $job->company->is_verified)
                                        <i class="bi bi-check-circle-fill text-success ms-1" title="Verified"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3">
                                <span class="badge bg-light text-dark me-1"><i class="bi bi-geo-alt me-1"></i>{{ $job->location ?? 'Remote' }}</span>
                                <span class="badge bg-light text-dark me-1"><i class="bi bi-clock me-1"></i>{{ ucfirst(str_replace('-', ' ', $job->job_type)) }}</span>
                                <span class="badge bg-light text-dark"><i class="bi bi-laptop me-1"></i>{{ ucfirst($job->work_mode) }}</span>
                            </div>
                            <p class="card-text text-muted small">{{ Str::limit(strip_tags($job->description), 100) }}</p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-primary fw-bold">{{ $job->salary_range }}</span>
                                <small class="text-muted">{{ $job->days_ago }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-briefcase display-1 text-secondary"></i>
                    <p class="text-muted mt-3">No featured jobs available at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h3>Browse by Category</h3>
            <p class="text-muted">Explore opportunities across different industries</p>
        </div>
        <div class="row g-4">
            @foreach($categories->take(6) as $category)
                <div class="col-lg-2 col-md-4 col-6">
                    <a href="{{ route('categories.show', $category->slug) }}" class="text-decoration-none">
                        <div class="card h-100 text-center border-0 shadow-sm category-card">
                            <div class="card-body">
                                <i class="{{ $category->icon ?? 'bi bi-grid' }} display-4 text-primary mb-3"></i>
                                <h6 class="mb-1">{{ $category->name }}</h6>
                                <small class="text-muted">{{ $category->active_jobs_count ?? 0 }} jobs</small>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h3>Recent Jobs</h3>
        </div>
        <div class="row">
            <div class="col-lg-8">
                @forelse($recentJobs as $job)
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2 col-3">
                                    @if($job->company && $job->company->logo)
                                        <img src="{{ Storage::url($job->company->logo) }}" alt="{{ $job->company->name }}" class="img-fluid rounded">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="bi bi-building text-secondary"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-7 col-9">
                                    <h5 class="mb-1">
                                        <a href="{{ route('jobs.show', $job->slug) }}" class="text-decoration-none text-dark">{{ $job->title }}</a>
                                    </h5>
                                    <p class="mb-1 text-muted small">{{ $job->company->name ?? 'Company' }}</p>
                                    <div>
                                        <span class="badge bg-light text-dark me-1">{{ $job->location ?? 'Remote' }}</span>
                                        <span class="badge bg-light text-dark">{{ ucfirst($job->job_type) }}</span>
                                    </div>
                                </div>
                                <div class="col-md-3 text-md-end mt-3 mt-md-0">
                                    <p class="text-primary fw-bold mb-1">{{ $job->salary_range }}</p>
                                    <small class="text-muted">{{ $job->days_ago }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <p class="text-muted">No recent jobs available.</p>
                    </div>
                @endforelse
            </div>
            <div class="col-lg-4">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-briefcase-fill display-1 mb-3"></i>
                        <h4>{{ $totalJobs }}+</h4>
                        <p>Active Jobs</p>
                        <hr class="bg-white opacity-25">
                        <h4>{{ $totalCompanies }}+</h4>
                        <p>Companies Hiring</p>
                        <a href="{{ route('register') }}?role=jobseeker" class="btn btn-light mt-3">Create Free Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h3 class="mb-3">Are You Hiring?</h3>
        <p class="lead mb-4">Join thousands of companies finding the best talent on our platform.</p>
        <a href="{{ route('register') }}?role=recruiter" class="btn btn-light btn-lg">Post a Job for Free</a>
    </div>
</section>
@endsection
