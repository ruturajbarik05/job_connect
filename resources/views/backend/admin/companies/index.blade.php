@extends('layouts.backend')
@section('title', 'Manage Companies')

@section('content')
<div class="container-fluid py-4">
    <h4 class="mb-4">Manage Companies</h4>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Company</th>
                        <th>Industry</th>
                        <th>Owner</th>
                        <th>Status</th>
                        <th>Verified</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $company)
                        <tr>
                            <td>{{ $company->name }}</td>
                            <td>{{ $company->industry ?? 'N/A' }}</td>
                            <td>{{ $company->user->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $company->status == 'approved' ? 'success' : ($company->status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($company->status) }}
                                </span>
                            </td>
                            <td>
                                @if($company->is_verified)
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                @else
                                    <i class="bi bi-x-circle text-secondary"></i>
                                @endif
                            </td>
                            <td>
                                @if($company->status === 'pending')
                                    <form action="{{ route('admin.companies.approve', $company) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.companies.reject', $company) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                    </form>
                                @else
                                    @if($company->is_verified)
                                        <form action="{{ route('admin.companies.unverify', $company) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">Unverify</button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.companies.verify', $company) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">Verify</button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No companies found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="d-flex justify-content-center">
                {{ $companies->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
