@extends('layouts.backend')
@section('title', 'Manage Applications')

@section('content')
<div class="container-fluid py-4">
    <h4 class="mb-4">Manage Applications</h4>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Job</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th>Applied</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                        <tr>
                            <td>{{ $application->user->name }}</td>
                            <td>{{ $application->job->title }}</td>
                            <td>{{ $application->job->company->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $application->status == 'applied' ? 'primary' : ($application->status == 'shortlisted' ? 'success' : 'secondary') }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </td>
                            <td>{{ $application->applied_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No applications found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="d-flex justify-content-center">
                {{ $applications->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
