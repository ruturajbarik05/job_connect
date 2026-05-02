@extends('layouts.backend')
@section('title', 'Applications')

@section('content')
<div class="container-fluid py-4">
    <h4 class="mb-4">Applications</h4>
    
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('recruiter.applications.index') }}" class="row g-2 mb-3">
                <div class="col-md-5">
                    <select name="job" class="form-select">
                        <option value="">All Jobs</option>
                        @foreach($jobs as $jobOption)
                            <option value="{{ $jobOption->id }}" {{ optional($job)->id === $jobOption->id ? 'selected' : '' }}>
                                {{ $jobOption->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="all">All Statuses</option>
                        @foreach(['applied', 'viewed', 'shortlisted', 'interview', 'offer', 'rejected', 'withdrawn'] as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Job</th>
                            <th>Applied Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($application->user->avatar)
                                            <img src="{{ Storage::url($application->user->avatar) }}" alt="" class="rounded-circle me-2" width="40" height="40">
                                        @else
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white me-2" style="width: 40px; height: 40px;">
                                                {{ substr($application->user->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <strong>{{ $application->user->name }}</strong>
                                            <br><small class="text-muted">{{ $application->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('recruiter.applications.show', $application) }}">{{ $application->job->title }}</a>
                                </td>
                                <td>{{ $application->applied_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $application->status == 'applied' ? 'primary' : ($application->status == 'shortlisted' ? 'success' : ($application->status == 'rejected' ? 'danger' : 'info')) }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('recruiter.applications.show', $application) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No applications found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $applications->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
