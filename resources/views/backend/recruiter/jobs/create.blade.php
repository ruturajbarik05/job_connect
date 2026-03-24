@extends('layouts.backend')
@section('title', 'Create Job')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Post New Job</h4>
        <a href="{{ route('recruiter.jobs.index') }}" class="btn btn-secondary">Back</a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('recruiter.jobs.store') }}">
                @csrf
                <div class="row g-4">
                    <div class="col-md-8">
                        <label class="form-label">Job Title *</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">Select category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Job Description *</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Requirements</label>
                        <textarea name="requirements" class="form-control" rows="4">{{ old('requirements') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Responsibilities</label>
                        <textarea name="responsibilities" class="form-control" rows="4">{{ old('responsibilities') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Benefits</label>
                        <textarea name="benefits" class="form-control" rows="3">{{ old('benefits') }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Job Type *</label>
                        <select name="job_type" class="form-select" required>
                            <option value="full-time" {{ old('job_type') == 'full-time' ? 'selected' : '' }}>Full Time</option>
                            <option value="part-time" {{ old('job_type') == 'part-time' ? 'selected' : '' }}>Part Time</option>
                            <option value="contract" {{ old('job_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="internship" {{ old('job_type') == 'internship' ? 'selected' : '' }}>Internship</option>
                            <option value="freelance" {{ old('job_type') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Work Mode *</label>
                        <select name="work_mode" class="form-select" required>
                            <option value="onsite" {{ old('work_mode') == 'onsite' ? 'selected' : '' }}>Onsite</option>
                            <option value="remote" {{ old('work_mode') == 'remote' ? 'selected' : '' }}>Remote</option>
                            <option value="hybrid" {{ old('work_mode') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Experience Level</label>
                        <input type="text" name="experience_level" class="form-control" value="{{ old('experience_level') }}" placeholder="e.g., Senior">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Minimum Salary</label>
                        <input type="number" name="salary_min" class="form-control" value="{{ old('salary_min') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Maximum Salary</label>
                        <input type="number" name="salary_max" class="form-control" value="{{ old('salary_max') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Required Skills (comma separated)</label>
                        <input type="text" name="skills" class="form-control" value="{{ old('skills') }}" placeholder="PHP, Laravel, MySQL...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Vacancies</label>
                        <input type="number" name="vacancies" class="form-control" value="{{ old('vacancies', 1) }}" min="1">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Application Deadline</label>
                        <input type="date" name="application_deadline" class="form-control" value="{{ old('application_deadline') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-4">Post Job</button>
            </form>
        </div>
    </div>
</div>
@endsection
