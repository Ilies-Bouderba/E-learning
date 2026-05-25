<header class="nav-wrap">
    <div class="container nav-inner">
        <a href="{{ route('home') }}" class="nav-logo">edu<span>me</span>x</a>

        <nav class="nav-links">
            @auth
                @if(auth()->user()->isTeacher())
                    <a href="{{ route('teacher.cours.index') }}" class="{{ request()->routeIs('teacher.cours.*') ? 'active' : '' }}">My Courses</a>
                    <a href="{{ route('teacher.dashboard') }}" class="{{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">Dashboard</a>
                @elseif(auth()->user()->isStudent())
                    <a href="{{ route('student.cours.index') }}" class="{{ request()->routeIs('student.cours.*') ? 'active' : '' }}">Browse Courses</a>
                    <a href="{{ route('student.dashboard') }}" class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">Dashboard</a>
                @elseif(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}">Admin Panel</a>
                @endif
            @endauth
            @guest
                <a href="{{ route('home') }}">Home</a>
                <a href="#">About</a>
            @endguest
        </nav>

        <div class="nav-actions">
            @auth
                <div class="nav-notif" id="notifWrap">
                    <button class="nav-notif-btn" id="notifBtn" title="Notifications">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                        @php $unread = auth()->user()->unreadNotifications->count(); @endphp
                        @if($unread > 0)
                            <span class="notif-badge">{{ $unread > 9 ? '9+' : $unread }}</span>
                        @endif
                    </button>
                    <div class="notif-dropdown" id="notifDropdown">
                        <div class="notif-header">
                            <span class="notif-title">Notifications</span>
                            @if($unread > 0)
                                <button onclick="markAllRead()" class="notif-mark-all">Mark all read</button>
                            @endif
                        </div>
                        <div class="notif-list">
                            @forelse(auth()->user()->notifications->take(10) as $notif)
                                <div class="notif-item {{ $notif->read_at ? '' : 'unread' }}" onclick="readNotif('{{ $notif->id }}', this)">
                                    <div class="notif-icon">
                                        @if(($notif->data['type'] ?? '') === 'announcement')
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 11 19-9-9 19-2-8-8-2z"/></svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                                        @endif
                                    </div>
                                    <div class="notif-body">
                                        <div class="notif-text">{{ $notif->data['title'] ?? 'Notification' }}</div>
                                        @if(!empty($notif->data['course_title']))
                                            <div class="notif-sub">{{ $notif->data['course_title'] }}</div>
                                        @endif
                                        <div class="notif-time">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</div>
                                    </div>
                                </div>
                            @empty
                                <div class="notif-empty">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                                    <p>No notifications yet</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="user-menu">
                    <button class="user-menu-btn" id="userMenuBtn">
                        <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                        <span class="user-name">{{ auth()->user()->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="user-dropdown" id="userDropdown">
                        <div class="dropdown-header">
                            <div class="dropdown-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                            <div>
                                <div class="dropdown-name">{{ auth()->user()->name }}</div>
                                <div class="dropdown-email">{{ auth()->user()->email }}</div>
                                <span class="user-role-badge {{ auth()->user()->role }}">{{ ucfirst(auth()->user()->role) }}</span>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        @if(auth()->user()->isTeacher())
                            <a href="{{ route('teacher.dashboard') }}" class="dropdown-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                                Dashboard
                            </a>
                            <a href="{{ route('teacher.cours.index') }}" class="dropdown-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                                My Courses
                            </a>
                            <a href="{{ route('teacher.cours.create') }}" class="dropdown-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                                Create Course
                            </a>
                        @elseif(auth()->user()->isStudent())
                            <a href="{{ route('student.dashboard') }}" class="dropdown-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                                Dashboard
                            </a>
                            <a href="{{ route('student.cours.index') }}" class="dropdown-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                                Browse Courses
                            </a>
                        @elseif(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="dropdown-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                Admin Dashboard
                            </a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <button wire:click="logout" class="dropdown-link dropdown-logout">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Sign out
                        </button>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-ghost">Log in</a>
                
            @endauth
        </div>

        <button class="nav-burger" id="navBurger"><span></span><span></span><span></span></button>
    </div>

    <div class="nav-mobile" id="navMobile">
        @auth
            <div class="mobile-user-info">
                <div class="mobile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                <div>
                    <div class="mobile-user-name">{{ auth()->user()->name }}</div>
                    <div class="mobile-user-role">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
            </div>
            <div class="mobile-divider"></div>
            @if(auth()->user()->isTeacher())
                <a href="{{ route('teacher.cours.index') }}">My Courses</a>
                <a href="{{ route('teacher.dashboard') }}">Dashboard</a>
                <a href="{{ route('teacher.cours.create') }}">Create Course</a>
            @elseif(auth()->user()->isStudent())
                <a href="{{ route('student.cours.index') }}">Browse Courses</a>
                <a href="{{ route('student.dashboard') }}">Dashboard</a>
            @elseif(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
            @endif
            <div class="mobile-divider"></div>
            <button wire:click="logout" class="btn btn-primary" style="width:100%;">Sign out</button>
        @else
            <a href="{{ route('home') }}">Home</a>
            <div class="mobile-divider"></div>
            <a href="{{ route('login') }}" class="btn btn-ghost">Log in</a>
            
        @endauth
    </div>
</header>

<script>
document.getElementById('navBurger')?.addEventListener('click', () => document.getElementById('navMobile')?.classList.toggle('open'));

const userMenuBtn = document.getElementById('userMenuBtn');
const userDropdown = document.getElementById('userDropdown');
if (userMenuBtn && userDropdown) {
    userMenuBtn.addEventListener('click', e => { e.stopPropagation(); userDropdown.classList.toggle('show'); });
    document.addEventListener('click', () => userDropdown.classList.remove('show'));
}

const notifBtn = document.getElementById('notifBtn');
const notifDropdown = document.getElementById('notifDropdown');
if (notifBtn && notifDropdown) {
    notifBtn.addEventListener('click', e => { e.stopPropagation(); notifDropdown.classList.toggle('show'); });
    document.addEventListener('click', e => {
        if (!notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) notifDropdown.classList.remove('show');
    });
}

function readNotif(id, el) {
    el.classList.remove('unread');
    const badge = document.querySelector('.notif-badge');
    if (badge) { const c = parseInt(badge.textContent) - 1; if (c <= 0) badge.remove(); else badge.textContent = c > 9 ? '9+' : c; }
    fetch('/notifications/' + id + '/read', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '' } });
}

function markAllRead() {
    document.querySelectorAll('.notif-item.unread').forEach(el => el.classList.remove('unread'));
    document.querySelector('.notif-badge')?.remove();
    document.querySelector('.notif-mark-all')?.remove();
    fetch('/notifications/read-all', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '' } });
}
</script>
