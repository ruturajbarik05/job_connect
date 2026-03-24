@extends('layouts.backend')
@section('title', 'Edit Job')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Edit Job</h4>
        <a href="{{ route('recruiter.jobs.index') }}" class="btn btn-secondary">Back</a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('recruiter.jobs.update', $job) }}">
                @csrf @method('PUT')
                <div class="row g-4">
                    <div class="col-md-8">
                        <label class="form-label">Job Title *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $job->title) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">Select category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $job->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Job Description *</label>
                        <textarea name="description" class="form-control" rows="5" required>{{ old('description', $job->description) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Requirements</label>
                        <textarea name="requirements" class="form-control" rows="4">{{ old('requirements', $job->requirements) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Responsibilities</label>
                        <textarea name="responsibilities" class="form-control" rows="4">{{ old('responsibilities', $job->responsibilities) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Benefits</label>
                        <textarea name="benefits" class="form-control" rows="3">{{ old('benefits', $job->benefits) }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location', $job->location) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Job Type *</label>
                        <select name="job_type" class="form-select" required>
                            <option value="full-time" {{ $job->job_type == 'full-time' ? 'selected' : '' }}>Full Time</option>
                            <option value="part-time" {{ $job->job_type == 'part-time' ? 'selected' : '' }}>Part Time</option>
                            <option value="contract" {{ $job->job_type == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="internship" {{ $job->job_type == 'internship' ? 'selected' : '' }}>Internship</option>
                            <option value="freelance" {{ $job->job_type == 'freelance' ? 'selected' : '' }}>Freelance</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Work Mode *</label>
                        <select name="work_mode" class="form-select" required>
                            <option value="onsite" {{ $job->work_mode == 'onsite' ? 'selected' : '' }}>Onsite</option>
                            <option value="remote" {{ $job->work_mode == 'remote' ? 'selected' : '' }}>Remote</option>
                            <option value="hybrid" {{ $job->work_mode == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Experience Level</label>
                        <input type="text" name="experience_level" class="form-control" value="{{ old('experience_level', $job->experience_level) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Minimum Salary</label>
                        <input type="number" name="salary_min" class="form-control" value="{{ old('salary_min', $job->salary_min) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Maximum Salary</label>
                        <input type="number" name="salary_max" class="form-control" value="{{ old('salary_max', $job->salary_max) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Required Skills (comma separated)</label>
                        <input type="text" name="skills" class="form-control" value="{{ old('skills', is_array($job->skills_required) ? implode(', ', $job->skills_required) : '') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Vacancies</label>
                        <input type="number" name="vacancies" class="form-control" value="{{ old('vacancies', $job->vacancies) }}" min="1">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Application Deadline</label>
                        <input type="date" name="application_deadline" class="form-control" value="{{ old('application_deadline', $job->application_deadline?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ $job->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="pending" {{ $job->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="closed" {{ $job->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            <option value="draft" {{ $job->status == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-4">Update Job</button>
            </form>
        </div>
    </div>
</div>
@endsection
