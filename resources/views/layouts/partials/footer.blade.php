<footer class="bg-dark text-white mt-5 py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>JobPortal</h5>
                <p class="text-muted">Connecting talent with opportunity. Find your dream job or hire the best candidates.</p>
            </div>
            <div class="col-md-4">
                <h6>Quick Links</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('jobs.index') }}" class="text-muted">Browse Jobs</a></li>
                    <li><a href="{{ route('companies.index') }}" class="text-muted">Companies</a></li>
                    <li><a href="{{ route('categories.index') }}" class="text-muted">Categories</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6>For Recruiters</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('register') }}?role=recruiter" class="text-muted">Post a Job</a></li>
                    <li><a href="{{ route('login') }}" class="text-muted">Recruiter Login</a></li>
                </ul>
            </div>
        </div>
        <hr class="bg-secondary">
        <div class="text-center text-muted">
            <small>&copy; {{ date('Y') }} JobPortal. All rights reserved.</small>
        </div>
    </div>
</footer>
