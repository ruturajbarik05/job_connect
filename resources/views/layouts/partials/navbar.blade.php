<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <i class="bi bi-briefcase me-2"></i>JobPortal
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('jobs.index') }}">Jobs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('companies.index') }}">Companies</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('categories.index') }}">Categories</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-bell me-1"></i>
                            @if(auth()->user()->notifications->where('is_read', false)->count() > 0)
                                <span class="badge bg-danger">{{ auth()->user()->notifications->where('is_read', false)->count() }}</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @forelse(auth()->user()->notifications->take(5) as $notification)
                                <li>
                                    <a class="dropdown-item" href="{{ $notification->link ?? '#' }}">
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        <br>{{ Str::limit($notification->message, 50) }}
                                    </a>
                                </li>
                            @empty
                                <li><span class="dropdown-item text-muted">No notifications</span></li>
                            @endforelse
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(auth()->user()->isAdmin())
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                            @elseif(auth()->user()->isRecruiter())
                                <li><a class="dropdown-item" href="{{ route('recruiter.dashboard') }}">Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('recruiter.company.profile') }}">Company Profile</a></li>
                            @elseif(auth()->user()->isJobSeeker())
                                <li><a class="dropdown-item" href="{{ route('jobseeker.dashboard') }}">Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('jobseeker.profile.index') }}">My Profile</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
<div class="mt-5 pt-4"></div>
