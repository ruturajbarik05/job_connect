@extends('layouts.app')
@section('title', 'Companies')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Featured Companies</h2>
    <div class="row g-4">
        @forelse($companies as $company)
            <div class="col-lg-3 col-md-4 col-6">
                <a href="{{ route('companies.show', $company->slug) }}" class="text-decoration-none">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body py-4">
                            @if($company->logo)
                                <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->name }}" class="mb-3" style="height: 60px; object-fit: contain;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-building text-secondary fs-3"></i>
                                </div>
                            @endif
                            <h6 class="mb-1 text-dark">{{ $company->name }}</h6>
                            <small class="text-muted">{{ $company->active_jobs_count ?? 0 }} jobs</small>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">No companies found.</p>
            </div>
        @endforelse
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $companies->links() }}
    </div>
</div>
@endsection
