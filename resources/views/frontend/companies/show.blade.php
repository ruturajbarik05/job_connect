@extends('layouts.app')
@section('title', $company->name)

@section('content')
<div class="container py-5">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    @if($company->logo)
                        <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->name }}" class="img-fluid rounded" style="max-height: 100px;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                            <i class="bi bi-building text-secondary fs-1"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-7">
                    <h3 class="mb-2">
                        {{ $company->name }}
                        @if($company->is_verified)
                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Verified</span>
                        @endif
                    </h3>
                    <p class="text-muted mb-2">
                        @if($company->industry)<i class="bi bi-building me-1"></i>{{ $company->industry }}@endif
                        @if($company->company_size)<i class="bi bi-people me-1 ms-2"></i>{{ $company->company_size }} employees@endif
                    </p>
                    @if($company->location)
                        <p class="mb-0"><i class="bi bi-geo-alt me-1"></i>{{ $company->location }}</p>
                    @endif
                </div>
                <div class="col-md-3 text-md-end">
                    <span class="badge bg-primary">{{ $jobs->total() }} Open Positions</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            @if($company->description)
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h4>About Company</h4>
                        <p>{{ $company->description }}</p>
                    </div>
                </div>
            @endif
            
            <h4 class="mb-3">Open Positions</h4>
            @forelse($jobs as $job)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="mb-1">
                                    <a href="{{ route('jobs.show', $job->slug) }}" class="text-decoration-none text-dark">{{ $job->title }}</a>
                                </h5>
                                <div class="mb-2">
                                    <span class="badge bg-light text-dark me-2"><i class="bi bi-geo-alt me-1"></i>{{ $job->location ?? 'Remote' }}</span>
                                    <span class="badge bg-light text-dark"><i class="bi bi-clock me-1"></i>{{ ucfirst($job->job_type) }}</span>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <p class="text-primary fw-bold mb-2">{{ $job->salary_range }}</p>
                                <small class="text-muted">{{ $job->days_ago }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">No open positions at this company.</div>
            @endforelse
            
            <div class="d-flex justify-content-center">
                {{ $jobs->links() }}
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Company Info</h5>
                    <ul class="list-unstyled">
                        @if($company->website)
                            <li class="mb-2"><i class="bi bi-globe me-2"></i><a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a></li>
                        @endif
                        @if($company->founded_year)
                            <li class="mb-2"><i class="bi bi-calendar me-2"></i>Founded {{ $company->founded_year }}</li>
                        @endif
                        @if($company->company_size)
                            <li class="mb-2"><i class="bi bi-people me-2"></i>{{ $company->company_size }} employees</li>
                        @endif
                        @if($company->phone)
                            <li class="mb-2"><i class="bi bi-telephone me-2"></i>{{ $company->phone }}</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
