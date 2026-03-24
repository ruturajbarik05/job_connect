@extends('layouts.app')
@section('title', $category->name . ' Jobs')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">{{ $category->name }} Jobs</h2>
    
    @forelse($jobs as $job)
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
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
                        <p class="text-muted mb-2">{{ $job->company->name ?? 'Company' }}</p>
                        <div>
                            <span class="badge bg-light text-dark me-2"><i class="bi bi-geo-alt me-1"></i>{{ $job->location ?? 'Remote' }}</span>
                            <span class="badge bg-light text-dark"><i class="bi bi-clock me-1"></i>{{ ucfirst($job->job_type) }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 text-md-end mt-3 mt-md-0">
                        <p class="text-primary fw-bold mb-2">{{ $job->salary_range }}</p>
                        <small class="text-muted">{{ $job->days_ago }}</small>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center py-5">
            <i class="bi bi-briefcase display-4 text-secondary"></i>
            <h4 class="mt-3">No jobs in this category</h4>
            <p class="text-muted">Check back later for new opportunities.</p>
        </div>
    @endforelse
    
    <div class="d-flex justify-content-center">
        {{ $jobs->links() }}
    </div>
</div>
@endsection
