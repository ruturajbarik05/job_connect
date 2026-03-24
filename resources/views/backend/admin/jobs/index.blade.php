@extends('layouts.backend')
@section('title', 'Manage Jobs')

@section('content')
<div class="container-fluid py-4">
    <h4 class="mb-4">Manage Jobs</h4>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Company</th>
                        <th>Applications</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobs as $job)
                        <tr>
                            <td>
                                <a href="{{ route('jobs.show', $job->slug) }}" target="_blank">{{ $job->title }}</a>
                            </td>
                            <td>{{ $job->company->name ?? 'N/A' }}</td>
                            <td>{{ $job->applications_count }}</td>
                            <td>
                                <span class="badge bg-{{ $job->status == 'active' ? 'success' : ($job->status == 'pending' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </td>
                            <td>{{ $job->created_at->format('M d, Y') }}</td>
                            <td>
                                <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this job?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No jobs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="d-flex justify-content-center">
                {{ $jobs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
