@extends('layouts.backend')
@section('title', 'Manage Users')

@section('content')
<div class="container-fluid py-4">
    <h4 class="mb-4">Manage Users</h4>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $user->status == 'active' ? 'success' : ($user->status == 'suspended' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form action="{{ route('admin.users.status', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="form-select form-select-sm d-inline-block w-auto">
                                        <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="suspended" {{ $user->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                        <option value="banned" {{ $user->status == 'banned' ? 'selected' : '' }}>Banned</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
