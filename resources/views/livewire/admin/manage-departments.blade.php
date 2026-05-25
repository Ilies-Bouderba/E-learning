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
        </nav>
    </aside>
    <main class="dash-main">
        <div class="dash-header">
            <div><span class="section-tag">Admin Panel</span>
                <h1 class="dash-title">Departments</h1>
                <p class="dash-subtitle">Organize courses into departments.</p>
            </div>
            <button class="btn btn-primary" wire:click="openCreate">+ Add Department</button>
        </div>

        @if (session('success'))
            <div class="mc-flash">{{ session('success') }}</div>
        @endif

        @if ($showForm)
            <div class="mc-modal-overlay">
                <div class="mc-modal admin-modal">
                    <h3 class="mc-modal-title">{{ $editingId ? 'Edit Department' : 'Add Department' }}</h3>
                    <form wire:submit="save" class="admin-form">
                        <div class="cc-field">
                            <label class="cc-label">Icon</label>
                            <div class="icon-picker">
                                @foreach ($icons as $ico)
                                    <button type="button" class="icon-option {{ $icon === $ico ? 'icon-active' : '' }}"
                                        wire:click="$set('icon','{{ $ico }}')">{{ $ico }}</button>
                                @endforeach
                            </div>
                            @error('icon')
                                <span class="cc-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="cc-field"><label class="cc-label">Name</label><input type="text" class="cc-input"
                                wire:model="name" placeholder="e.g. Mathematics">
                            @error('name')
                                <span class="cc-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="cc-field"><label class="cc-label">Description <span
                                    class="cc-optional">(optional)</span></label><input type="text" class="cc-input"
                                wire:model="description" placeholder="Short description">
                            @error('description')
                                <span class="cc-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mc-modal-actions" style="margin-top:1.5rem;">
                            <button type="button" class="btn btn-ghost"
                                wire:click="$set('showForm',false)">Cancel</button>
                            <button type="submit" class="btn btn-primary"><span wire:loading.remove
                                    wire:target="save">{{ $editingId ? 'Save Changes' : 'Create' }} →</span><span
                                    wire:loading wire:target="save">Saving...</span></button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        @if ($deletingId)
            <div class="mc-modal-overlay">
                <div class="mc-modal">
                    <div class="mc-modal-icon">🗑️</div>
                    <h3 class="mc-modal-title">Delete this department?</h3>
                    <p class="mc-modal-sub">Courses in this department may be affected.</p>
                    <div class="mc-modal-actions">
                        <button class="btn btn-ghost" wire:click="cancelDelete">Cancel</button>
                        <button class="btn btn-danger" wire:click="delete">Yes, Delete</button>
                    </div>
                </div>
            </div>
        @endif

        <div class="dept-cards">
            @forelse($departments as $dept)
                <div class="dept-card">
                    <div class="dept-card-top">
                        <span class="dept-card-icon">{{ $dept->icon }}</span>
                        <div class="mc-actions">
                            <button class="mc-btn-edit" wire:click="openEdit({{ $dept->id }})">Edit</button>
                            <button class="mc-btn-delete"
                                wire:click="confirmDelete({{ $dept->id }})">Delete</button>
                        </div>
                    </div>
                    <div class="dept-card-name">{{ $dept->name }}</div>
                    <div class="dept-card-desc">{{ $dept->description ?: '—' }}</div>
                    <span class="dept-card-count">{{ $dept->courses_count }} courses</span>
                </div>
            @empty
                <div class="mc-empty"><span>🏛️</span>
                    <p>No departments yet.</p>
                </div>
            @endforelse
        </div>
    </main>
</div>
