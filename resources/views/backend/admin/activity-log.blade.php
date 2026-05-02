@extends('layouts.backend')
@section('title', 'Activity Log')

@section('content')
<div class="container-fluid py-4">
    <h4 class="mb-4">Activity Log</h4>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Admin</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>Target</th>
                            <th>IP Address</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>
                                    <strong>{{ $log->admin->name ?? 'Unknown' }}</strong>
                                    @if($log->admin)
                                        <br><small class="text-muted">{{ $log->admin->email }}</small>
                                    @endif
                                </td>
                                <td><span class="badge bg-secondary">{{ str_replace('_', ' ', $log->action) }}</span></td>
                                <td>{{ $log->description }}</td>
                                <td>
                                    @if($log->target_type)
                                        <small class="text-muted">{{ class_basename($log->target_type) }} #{{ $log->target_id }}</small>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $log->ip_address ?? 'N/A' }}</td>
                                <td>{{ $log->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No activity recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
