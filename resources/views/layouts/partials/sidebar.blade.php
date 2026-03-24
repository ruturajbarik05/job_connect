<aside class="sidebar bg-dark text-white">
    <div class="p-3 text-center border-bottom border-secondary">
        <i class="bi bi-briefcase fs-4"></i>
        <span class="ms-2 fw-bold">JobPortal</span>
    </div>
    <nav class="nav flex-column mt-3">
        @if(auth()->user()->isAdmin())
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                <i class="bi bi-people me-2"></i>Users
            </a>
            <a class="nav-link {{ request()->routeIs('admin.companies.*') ? 'active' : '' }}" href="{{ route('admin.companies.index') }}">
                <i class="bi bi-building me-2"></i>Companies
            </a>
            <a class="nav-link {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}" href="{{ route('admin.jobs.index') }}">
                <i class="bi bi-briefcase me-2"></i>Jobs
            </a>
            <a class="nav-link {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}" href="{{ route('admin.applications.index') }}">
                <i class="bi bi-file-earmark-text me-2"></i>Applications
            </a>
            <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                <i class="bi bi-grid me-2"></i>Categories
            </a>
        @elseif(auth()->user()->isRecruiter())
            <a class="nav-link {{ request()->routeIs('recruiter.dashboard') ? 'active' : '' }}" href="{{ route('recruiter.dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('recruiter.company.*') ? 'active' : '' }}" href="{{ route('recruiter.company.profile') }}">
                <i class="bi bi-building me-2"></i>Company Profile
            </a>
            <a class="nav-link {{ request()->routeIs('recruiter.jobs.*') ? 'active' : '' }}" href="{{ route('recruiter.jobs.index') }}">
                <i class="bi bi-briefcase me-2"></i>My Jobs
            </a>
            <a class="nav-link {{ request()->routeIs('recruiter.applications.*') ? 'active' : '' }}" href="{{ route('recruiter.applications.index') }}">
                <i class="bi bi-file-earmark-text me-2"></i>Applications
            </a>
            <a class="nav-link {{ request()->routeIs('recruiter.notifications.*') ? 'active' : '' }}" href="{{ route('recruiter.notifications.index') }}">
                <i class="bi bi-bell me-2"></i>Notifications
            </a>
        @elseif(auth()->user()->isJobSeeker())
            <a class="nav-link {{ request()->routeIs('jobseeker.dashboard') ? 'active' : '' }}" href="{{ route('jobseeker.dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('jobseeker.profile.*') ? 'active' : '' }}" href="{{ route('jobseeker.profile.index') }}">
                <i class="bi bi-person me-2"></i>My Profile
            </a>
            <a class="nav-link {{ request()->routeIs('jobseeker.applications.*') ? 'active' : '' }}" href="{{ route('jobseeker.applications.index') }}">
                <i class="bi bi-file-earmark-text me-2"></i>Applications
            </a>
            <a class="nav-link {{ request()->routeIs('jobseeker.saved-jobs.*') ? 'active' : '' }}" href="{{ route('jobseeker.saved-jobs.index') }}">
                <i class="bi bi-bookmark me-2"></i>Saved Jobs
            </a>
            <a class="nav-link {{ request()->routeIs('jobseeker.notifications.*') ? 'active' : '' }}" href="{{ route('jobseeker.notifications.index') }}">
                <i class="bi bi-bell me-2"></i>Notifications
            </a>
        @endif
    </nav>
</aside>
