<div class="dash-layout">
    <aside class="sidebar">
        <a href="{{ route('home') }}" class="sidebar-logo">edu<span>me</span>x</a>
        <nav class="sidebar-nav">
            <span class="sidebar-nav-label">Overview</span>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link active"><span class="sidebar-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></span>
                Dashboard</a>
            <span class="sidebar-nav-label">Manage</span>
            <a href="{{ route('admin.teachers') }}" class="sidebar-link"><span class="sidebar-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
                Teachers</a>
            <a href="{{ route('admin.students') }}" class="sidebar-link"><span class="sidebar-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg></span>
                Students</a>
            <a href="{{ route('admin.departments') }}" class="sidebar-link"><span class="sidebar-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="22" x2="21" y2="22"/><line x1="6" y1="18" x2="6" y2="11"/><line x1="10" y1="18" x2="10" y2="11"/><line x1="14" y1="18" x2="14" y2="11"/><line x1="18" y1="18" x2="18" y2="11"/><polygon points="12 2 20 7 4 7"/></svg></span>
                Departments</a>
            <a href="{{ route('admin.password-resets') }}" class="sidebar-link">
                <span class="sidebar-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21 2-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0 3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg></span> Password Resets
                @php $pendingResets = \App\Models\PasswordResetRequest::where("status","pending")->count(); @endphp
                @if($pendingResets > 0)
                    <span style="background:#ef4444;color:white;border-radius:999px;padding:0 6px;font-size:0.7rem;margin-left:auto;">{{ $pendingResets }}</span>
                @endif
            </a>
        </nav>
    </aside>
    <main class="dash-main">
        <div class="dash-header">
            <div>
                <h1 class="dash-title">Admin Dashboard 👑</h1>
                <p class="dash-subtitle">Platform overview and user management.</p>
            </div>
        </div>
        <div class="dash-stats">
            <div class="dash-stat-card dash-stat-yellow">
                <div class="dsc-icon">👨‍🏫</div>
                <div class="dsc-info"><span class="dsc-num">{{ $totalTeachers }}</span><span
                        class="dsc-label">Teachers</span></div>
            </div>
            <div class="dash-stat-card">
                <div class="dsc-icon">🎓</div>
                <div class="dsc-info"><span class="dsc-num">{{ $totalStudents }}</span><span
                        class="dsc-label">Students</span></div>
            </div>
            <div class="dash-stat-card">
                <div class="dsc-icon">🏛️</div>
                <div class="dsc-info"><span class="dsc-num">{{ $totalDepts }}</span><span
                        class="dsc-label">Departments</span></div>
            </div>
        </div>
        <div class="dash-grid">
            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Departments</h2><a href="{{ route('admin.departments') }}"
                        class="dash-card-link">Manage →</a>
                </div>
                <div class="dept-grid">
                    @foreach ($departments as $dept)
                        <div class="dept-item"><span class="dept-icon">{{ $dept->icon }}</span>
                            <div class="dept-info">
                                <div class="dept-name">{{ $dept->name }}</div>
                                <div class="dept-count">{{ $dept->courses_count }} courses</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Recent Teachers</h2><a href="{{ route('admin.teachers') }}"
                        class="dash-card-link">View all →</a>
                </div>
                <div class="students-list">
                    @foreach ($recentTeachers as $t)
                        <div class="student-item">
                            <div class="student-avatar sidebar-avatar-teacher">{{ strtoupper(substr($t->name, 0, 2)) }}
                            </div>
                            <div class="student-info">
                                <div class="student-name">{{ $t->name }}</div>
                                <div class="student-course">{{ $t->courses_count }} courses · {{ $t->email }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Recent Students</h2><a href="{{ route('admin.students') }}"
                        class="dash-card-link">View all →</a>
                </div>
                <div class="students-list">
                    @foreach ($recentStudents as $s)
                        <div class="student-item">
                            <div class="student-avatar">{{ strtoupper(substr($s->name, 0, 2)) }}</div>
                            <div class="student-info">
                                <div class="student-name">{{ $s->name }}</div>
                                <div class="student-course">{{ $s->enrollments_count }} enrollments ·
                                    {{ $s->email }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>
</div>
