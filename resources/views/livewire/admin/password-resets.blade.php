<div class="dash-layout">
    <aside class="sidebar">
        <a href="{{ route('home') }}" class="auth-logo" style="padding: 1.5rem; display: block; font-size: 1.4rem; font-weight: 900;">edu<span>me</span>x</a>
        <nav class="sidebar-nav">
            <span class="sidebar-nav-label">Overview</span>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><span class="sidebar-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></span> Dashboard</a>
            <span class="sidebar-nav-label">Manage</span>
            <a href="{{ route('admin.teachers') }}" class="sidebar-link"><span class="sidebar-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span> Teachers</a>
            <a href="{{ route('admin.students') }}" class="sidebar-link"><span class="sidebar-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg></span> Students</a>
            <a href="{{ route('admin.departments') }}" class="sidebar-link"><span class="sidebar-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="22" x2="21" y2="22"/><line x1="6" y1="18" x2="6" y2="11"/><line x1="10" y1="18" x2="10" y2="11"/><line x1="14" y1="18" x2="14" y2="11"/><line x1="18" y1="18" x2="18" y2="11"/><polygon points="12 2 20 7 4 7"/></svg></span> Departments</a>
            <a href="{{ route('admin.password-resets') }}" class="sidebar-link active">
                <span class="sidebar-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21 2-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0 3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg></span> Password Resets
                @if($pendingCount > 0)
                    <span style="background: #ef4444; color: white; border-radius: 999px; padding: 0 6px; font-size: 0.7rem; margin-left: auto;">{{ $pendingCount }}</span>
                @endif
            </a>
        </nav>
    </aside>

    <main class="dash-main">
        <div class="dash-header">
            <div>
                <h1 class="dash-title">🔑 Password Reset Requests</h1>
                <p class="dash-subtitle">Review and process student/teacher password reset requests.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mc-flash">{{ session('success') }}</div>
        @endif

        <div class="dash-card" style="margin-bottom: 2rem;">
            <div class="dash-card-header">
                <h2 class="dash-card-title">Pending Requests</h2>
                <span class="badge" style="{{ $pending->count() > 0 ? 'background: #ef4444; color: white;' : '' }}">
                    {{ $pending->count() }} pending
                </span>
            </div>

            @forelse($pending as $req)
                <div class="student-item" style="padding: 1rem; border-bottom: 1px solid rgba(15,14,23,0.08);">
                    <div class="student-avatar">{{ strtoupper(substr($req->user->name, 0, 2)) }}</div>
                    <div class="student-info">
                        <div class="student-name">{{ $req->user->name }}</div>
                        <div class="student-course">
                            {{ $req->user->email }} ·
                            <span style="background: #f59e0b; color: white; border-radius: 4px; padding: 1px 6px; font-size: 0.7rem;">{{ strtoupper($req->user->role) }}</span> ·
                            Requested {{ \Carbon\Carbon::parse($req->created_at)->diffForHumans() }}
                        </div>
                    </div>
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <button wire:click="openResolve({{ $req->id }})" class="btn btn-primary" style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">
                            Reset Password
                        </button>
                        <button wire:click="dismiss({{ $req->id }})" class="btn btn-ghost" style="font-size: 0.8rem; padding: 0.4rem 0.8rem;"
                            wire:confirm="Dismiss this request without resetting password?">
                            Dismiss
                        </button>
                    </div>
                </div>
            @empty
                <p class="empty-msg" style="padding: 2rem;">No pending requests. 🎉</p>
            @endforelse
        </div>

        @if($resolvingId)
            @php $req = $pending->firstWhere('id', $resolvingId) @endphp
            @if($req)
                <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; display: flex; align-items: center; justify-content: center;">
                    <div style="background: var(--c-bg); border: var(--border); border-radius: var(--radius); padding: 2rem; width: 100%; max-width: 480px;">
                        <h3 style="font-family: var(--font-head); font-size: 1.25rem; font-weight: 800; margin-bottom: 0.5rem;">Reset Password</h3>
                        <p style="color: var(--c-muted); margin-bottom: 1.5rem;">
                            Resetting password for <strong>{{ $req->user->name }}</strong> ({{ $req->user->email }}).<br>
                            The new password will be emailed to them.
                        </p>

                        <div class="form-group">
                            <label>New Password</label>
                            <input type="text" wire:model="newPassword" class="form-control"
                                style="font-family: monospace; font-size: 1rem; letter-spacing: 0.05em;">
                            @error('newPassword')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                            <small style="color: var(--c-muted);">You can edit this or use the auto-generated one.</small>
                        </div>

                        <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                            <button wire:click="resolve" class="btn btn-primary">
                                <span wire:loading.remove wire:target="resolve">✅ Reset & Send Email</span>
                                <span wire:loading wire:target="resolve">Sending...</span>
                            </button>
                            <button wire:click="cancelResolve" class="btn btn-ghost">Cancel</button>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <div class="dash-card">
            <div class="dash-card-header">
                <h2 class="dash-card-title">Recently Resolved</h2>
            </div>
            @forelse($resolved as $req)
                <div class="student-item" style="padding: 0.75rem 1rem; border-bottom: 1px solid rgba(15,14,23,0.06); opacity: 0.7;">
                    <div class="student-avatar" style="background: #10b981;">{{ strtoupper(substr($req->user->name, 0, 2)) }}</div>
                    <div class="student-info">
                        <div class="student-name">{{ $req->user->name }}</div>
                        <div class="student-course">{{ $req->user->email }} · Resolved {{ \Carbon\Carbon::parse($req->resolved_at)->diffForHumans() }}</div>
                    </div>
                    <span class="badge" style="background: #10b981; color: white;">Resolved</span>
                </div>
            @empty
                <p class="empty-msg" style="padding: 1rem;">No resolved requests yet.</p>
            @endforelse
        </div>
    </main>
</div>
