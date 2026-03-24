@extends('layouts.app')
@section('title', 'Job Categories')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center">Browse by Category</h2>
    <div class="row g-4">
        @forelse($categories as $category)
            <div class="col-lg-3 col-md-4 col-6">
                <a href="{{ route('categories.show', $category->slug) }}" class="text-decoration-none">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body">
                            <i class="{{ $category->icon ?? 'bi bi-grid' }} display-4 text-primary mb-3"></i>
                            <h6 class="mb-1">{{ $category->name }}</h6>
                            <small class="text-muted">{{ $category->active_jobs_count ?? 0 }} jobs</small>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">No categories found.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
