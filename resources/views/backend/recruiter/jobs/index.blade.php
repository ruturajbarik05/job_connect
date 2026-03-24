@extends('layouts.backend')
@section('title', 'My Jobs')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>My Jobs</h4>
        @if(auth()->user()->company && auth()->user()->company->status === 'approved')
            <a href="{{ route('recruiter.jobs.create') }}" class="btn btn-primary">Post New Job</a>
        @endif
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Category</th>
                            <th>Type</th>
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
                                <td>{{ $job->category->name ?? 'N/A' }}</td>
                                <td>{{ ucfirst($job->job_type) }}</td>
                                <td>{{ $job->applications_count }}</td>
                                <td>
                                    <span class="badge bg-{{ $job->status == 'active' ? 'success' : ($job->status == 'pending' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($job->status) }}
                                    </span>
                                </td>
                                <td>{{ $job->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('recruiter.jobs.edit', $job) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('recruiter.applications.index', ['job' => $job->id]) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-people"></i>
                                    </a>
                                    <form action="{{ route('recruiter.jobs.destroy', $job) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">No jobs posted yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $jobs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
