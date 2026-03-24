@extends('layouts.backend')
@section('title', 'Job Categories')

@section('content')
<div class="container-fluid py-4">
    <h4 class="mb-4">Job Categories</h4>
    
    <div class="card mb-4">
        <div class="card-body">
            <h5>Add New Category</h5>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <input type="text" name="name" class="form-control" placeholder="Category Name" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="icon" class="form-control" placeholder="Bootstrap Icon (e.g., bi-laptop)">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Add</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Icon</th>
                        <th>Active Jobs</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td><i class="{{ $category->icon ?? 'bi bi-grid' }}"></i></td>
                            <td>{{ $category->active_jobs_count ?? 0 }}</td>
                            <td>
                                <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this category?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="d-flex justify-content-center">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
