<header class="topbar bg-white shadow-sm p-3 d-flex justify-content-between align-items-center">
    <h4 class="mb-0">@yield('page-title', 'Dashboard')</h4>
    <div class="d-flex align-items-center">
        <div class="dropdown">
            <a class="nav-link dropdown-toggle text-dark" href="#" role="button" data-bs-toggle="dropdown">
                <i class="bi bi-bell me-1"></i>
                @if(auth()->user()->appNotifications->where('is_read', false)->count() > 0)
                    <span class="badge bg-danger">{{ auth()->user()->appNotifications->where('is_read', false)->count() }}</span>
                @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                @forelse(auth()->user()->appNotifications->take(5) as $notification)
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
        </div>
        <div class="dropdown ms-3">
            <a class="nav-link dropdown-toggle text-dark" href="#" role="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
