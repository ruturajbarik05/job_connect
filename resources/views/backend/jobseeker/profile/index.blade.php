@extends('layouts.backend')
@section('title', 'My Profile')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover;">
                    @else
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 120px; height: 120px;">
                            <i class="bi bi-person text-white" style="font-size: 48px;"></i>
                        </div>
                    @endif
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ $profile->experience_level ?? 'Not specified' }} Level</p>
                    
                    @if($profile->resume)
                        <a href="{{ Storage::url($profile->resume) }}" target="_blank" class="btn btn-outline-primary btn-sm mb-2">
                            <i class="bi bi-download me-1"></i>Download Resume
                        </a>
                    @endif
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Contact Information</h5>
                </div>
                <div class="card-body">
                    <p><i class="bi bi-envelope me-2"></i>{{ $user->email }}</p>
                    <p><i class="bi bi-telephone me-2"></i>{{ $profile->phone ?? 'Not provided' }}</p>
                    <p><i class="bi bi-geo-alt me-2"></i>{{ $profile->location ?? 'Not provided' }}</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Skills</h5>
                </div>
                <div class="card-body">
                    @if($profile && $profile->skills)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($profile->skills as $skill)
                                <span class="badge bg-primary">{{ $skill }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No skills added yet.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <form method="POST" action="{{ route('jobseeker.profile.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" value="{{ $profile->first_name ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="{{ $profile->last_name ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ $profile->phone ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" value="{{ $profile->city ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">State</label>
                                <input type="text" name="state" class="form-control" value="{{ $profile->state ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Country</label>
                                <input type="text" name="country" class="form-control" value="{{ $profile->country ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Experience Level</label>
                                <select name="experience_level" class="form-select">
                                    <option value="">Select</option>
                                    <option value="fresher" {{ ($profile->experience_level ?? '') == 'fresher' ? 'selected' : '' }}>Fresher</option>
                                    <option value="mid" {{ ($profile->experience_level ?? '') == 'mid' ? 'selected' : '' }}>Mid Level</option>
                                    <option value="senior" {{ ($profile->experience_level ?? '') == 'senior' ? 'selected' : '' }}>Senior</option>
                                    <option value="lead" {{ ($profile->experience_level ?? '') == 'lead' ? 'selected' : '' }}>Lead</option>
                                    <option value="executive" {{ ($profile->experience_level ?? '') == 'executive' ? 'selected' : '' }}>Executive</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Expected Salary</label>
                                <input type="number" name="expected_salary" class="form-control" value="{{ $profile->expected_salary ?? '' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Professional Summary</label>
                                <textarea name="summary" class="form-control" rows="4">{{ $profile->summary ?? '' }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Skills (comma separated)</label>
                                <input type="text" name="skills" class="form-control" value="{{ is_array($profile->skills ?? null) ? implode(', ', $profile->skills) : '' }}" placeholder="PHP, Laravel, JavaScript...">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Avatar</label>
                                <input type="file" name="avatar" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </div>
            </form>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Upload Resume</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('jobseeker.profile.resume') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <input type="file" name="resume" class="form-control" accept=".pdf,.doc,.docx" required>
                                <small class="text-muted">Accepted formats: PDF, DOC, DOCX (Max 5MB)</small>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-success">Upload Resume</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Education</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('jobseeker.education.store') }}" class="mb-4">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="institution" class="form-control" placeholder="Institution" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="degree" class="form-control" placeholder="Degree" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="field_of_study" class="form-control" placeholder="Field of Study" required>
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="start_date" class="form-control" placeholder="Start Date">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="end_date" class="form-control" placeholder="End Date">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-sm">Add Education</button>
                            </div>
                        </div>
                    </form>
                    
                    @forelse($education as $edu)
                        <div class="border p-3 mb-2 rounded">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $edu->institution }}</strong>
                                    <p class="mb-0 text-muted">{{ $edu->degree }} in {{ $edu->field_of_study }}</p>
                                    <small class="text-muted">
                                        {{ $edu->start_date?->format('Y') ?? 'N/A' }} - {{ $edu->is_current ? 'Present' : ($edu->end_date?->format('Y') ?? 'N/A') }}
                                    </small>
                                </div>
                                <form action="{{ route('jobseeker.education.destroy', $edu->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No education added yet.</p>
                    @endforelse
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Work Experience</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('jobseeker.experience.store') }}" class="mb-4">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="job_title" class="form-control" placeholder="Job Title" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="company_name" class="form-control" placeholder="Company Name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="location" class="form-control" placeholder="Location">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="start_date" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="end_date" class="form-control">
                            </div>
                            <div class="col-12">
                                <textarea name="description" class="form-control" rows="2" placeholder="Description"></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-sm">Add Experience</button>
                            </div>
                        </div>
                    </form>
                    
                    @forelse($experiences as $exp)
                        <div class="border p-3 mb-2 rounded">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $exp->job_title }}</strong>
                                    <p class="mb-0 text-muted">{{ $exp->company_name }}</p>
                                    <small class="text-muted">
                                        {{ $exp->start_date?->format('M Y') ?? 'N/A' }} - {{ $exp->is_current ? 'Present' : ($exp->end_date?->format('M Y') ?? 'N/A') }}
                                    </small>
                                </div>
                                <form action="{{ route('jobseeker.experience.destroy', $exp->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                            @if($exp->description)
                                <p class="mt-2 mb-0 small">{{ $exp->description }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted">No experience added yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
