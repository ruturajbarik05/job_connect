@extends('layouts.backend')
@section('title', 'Saved Jobs')

@section('content')
<div class="container-fluid py-4">
    <h4 class="mb-4">Saved Jobs</h4>
    
    <div class="row">
        @forelse($jobs as $job)
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                @if($job->company && $job->company->logo)
                                    <img src="{{ Storage::url($job->company->logo) }}" alt="" width="60" class="rounded">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        <i class="bi bi-building text-secondary"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">
                                    <a href="{{ route('jobs.show', $job->slug) }}" class="text-decoration-none">{{ $job->title }}</a>
                                </h5>
                                <p class="text-muted mb-2">{{ $job->company->name ?? 'Company' }}</p>
                                <div class="mb-2">
                                    <span class="badge bg-light text-dark me-1"><i class="bi bi-geo-alt me-1"></i>{{ $job->location ?? 'Remote' }}</span>
                                    <span class="badge bg-light text-dark"><i class="bi bi-clock me-1"></i>{{ ucfirst($job->job_type) }}</span>
                                </div>
                                <span class="text-primary fw-bold">{{ $job->salary_range }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Saved {{ $job->pivot->created_at->diffForHumans() }}</small>
                            <form action="{{ route('jobs.save', $job) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-bookmark display-1 text-secondary"></i>
                <h4 class="mt-3">No saved jobs</h4>
                <p class="text-muted">Save jobs you're interested in to view them later.</p>
                <a href="{{ route('jobs.index') }}" class="btn btn-primary">Browse Jobs</a>
            </div>
        @endforelse
    </div>
    
    @if($jobs->hasPages())
        <div class="d-flex justify-content-center">
            {{ $jobs->links() }}
        </div>
    @endif
</div>
@endsection
