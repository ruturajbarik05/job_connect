@extends('layouts.backend')
@section('title', 'Company Profile')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Company Profile</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('recruiter.company.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Company Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $company->name ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Industry</label>
                        <input type="text" name="industry" class="form-control" value="{{ old('industry', $company->industry ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Website</label>
                        <input type="url" name="website" class="form-control" value="{{ old('website', $company->website ?? '') }}" placeholder="https://">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Company Size</label>
                        <select name="company_size" class="form-select">
                            <option value="">Select size</option>
                            <option value="1-10" {{ ($company->company_size ?? '') == '1-10' ? 'selected' : '' }}>1-10 employees</option>
                            <option value="10-50" {{ ($company->company_size ?? '') == '10-50' ? 'selected' : '' }}>10-50 employees</option>
                            <option value="50-100" {{ ($company->company_size ?? '') == '50-100' ? 'selected' : '' }}>50-100 employees</option>
                            <option value="100-500" {{ ($company->company_size ?? '') == '100-500' ? 'selected' : '' }}>100-500 employees</option>
                            <option value="500+" {{ ($company->company_size ?? '') == '500+' ? 'selected' : '' }}>500+ employees</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location', $company->location ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $company->phone ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $company->email ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Founded Year</label>
                        <input type="text" name="founded_year" class="form-control" value="{{ old('founded_year', $company->founded_year ?? '') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $company->description ?? '') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Logo</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        @if($company && $company->logo)
                            <img src="{{ Storage::url($company->logo) }}" alt="Logo" class="mt-2" width="100">
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Banner</label>
                        <input type="file" name="banner" class="form-control" accept="image/*">
                        @if($company && $company->banner)
                            <img src="{{ Storage::url($company->banner) }}" alt="Banner" class="mt-2" width="200">
                        @endif
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-4">Save Changes</button>
            </form>
        </div>
    </div>
</div>
@endsection
