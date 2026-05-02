@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4">Create Your Account</h3>
                    
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong>Please fix the following:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label">I want to:</label>
                            <div class="d-flex gap-3">
                                <div class="form-check flex-fill">
                                    <input class="form-check-input role-option" type="radio" name="role" id="jobseeker" value="jobseeker" {{ old('role', $selectedRole ?? 'jobseeker') == 'jobseeker' ? 'checked' : '' }}>
                                    <label class="form-check-label card p-3 w-100" for="jobseeker">
                                        <strong>Find a Job</strong>
                                        <small class="d-block text-muted">Search and apply for jobs</small>
                                    </label>
                                </div>
                                <div class="form-check flex-fill">
                                    <input class="form-check-input role-option" type="radio" name="role" id="recruiter" value="recruiter" {{ old('role', $selectedRole ?? 'jobseeker') == 'recruiter' ? 'checked' : '' }}>
                                    <label class="form-check-label card p-3 w-100" for="recruiter">
                                        <strong>Hire Talent</strong>
                                        <small class="d-block text-muted">Post jobs and find candidates</small>
                                    </label>
                                </div>
                            </div>
                            @error('role')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="company-name-field">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name') }}">
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimum 8 characters</small>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 mb-3">Create Account</button>
                    </form>
                    
                    <p class="text-center text-muted small mb-0">
                        By registering, you agree to our Terms of Service and Privacy Policy.
                    </p>
                    
                    <hr class="my-4">
                    
                    <p class="text-center mb-0">
                        Already have an account? <a href="{{ route('login') }}">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const toggleCompanyName = () => {
        const field = document.getElementById('company-name-field');
        const input = field.querySelector('input');
        const isRecruiter = document.getElementById('recruiter').checked;

        field.classList.toggle('d-none', !isRecruiter);
        input.toggleAttribute('required', isRecruiter);
    };

    document.querySelectorAll('.role-option').forEach((option) => {
        option.addEventListener('change', toggleCompanyName);
    });

    toggleCompanyName();
</script>
@endpush
