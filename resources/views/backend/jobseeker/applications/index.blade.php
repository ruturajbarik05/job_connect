@extends('layouts.backend')
@section('title', 'My Applications')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>My Applications</h4>
        <a href="{{ route('jobs.index') }}" class="btn btn-primary">Browse Jobs</a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Job</th>
                            <th>Company</th>
                            <th>Applied Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
                            <tr>
                                <td>
                                    <a href="{{ route('jobs.show', $application->job->slug) }}" class="text-decoration-none">
                                        {{ $application->job->title }}
                                    </a>
                                </td>
                                <td>{{ $application->job->company->name ?? 'Company' }}</td>
                                <td>{{ $application->applied_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $application->status == 'shortlisted' ? 'success' : ($application->status == 'rejected' ? 'danger' : ($application->status == 'interview' ? 'warning' : 'primary')) }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($application->status == 'applied')
                                        <form action="{{ route('jobseeker.applications.withdraw', $application) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">Withdraw</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No applications yet.</td>
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
