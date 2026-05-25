<div class="dash-layout">
    <aside class="sidebar">
        <a href="{{ route('home') }}" class="sidebar-logo">edu<span>me</span>x</a>
        <nav class="sidebar-nav">
            <span class="sidebar-nav-label">Overview</span>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><span class="sidebar-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></span>
                Dashboard</a>
            <span class="sidebar-nav-label">Manage</span>
            <a href="{{ route('admin.teachers') }}" class="sidebar-link active"><span class="sidebar-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
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
                <h1 class="dash-title">Teachers</h1>
                <p class="dash-subtitle">Add, edit or remove teacher accounts.</p>
            </div>
            <button class="btn btn-primary" wire:click="openCreate">+ Add Teacher</button>
        </div>

        @if (session('success'))
            <div class="mc-flash">{{ session('success') }}</div>
        @endif

        @if ($showForm)
            <div class="mc-modal-overlay">
                <div class="mc-modal admin-modal">
                    <h3 class="mc-modal-title">{{ $editingId ? 'Edit Teacher' : 'Add Teacher' }}</h3>
                    <form wire:submit="save" class="admin-form">
                        <div class="cc-field"><label class="cc-label">Full Name</label><input type="text"
                                class="cc-input" wire:model="name" placeholder="Sara Ahmed">
                            @error('name')
                                <span class="cc-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="cc-field"><label class="cc-label">Email</label><input type="email"
                                class="cc-input" wire:model="email" placeholder="sara@email.com">
                            @error('email')
                                <span class="cc-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="cc-field"><label class="cc-label">Password
                                {{ $editingId ? '— leave blank to keep current' : '' }}</label><input type="password"
                                class="cc-input" wire:model="password" placeholder="Min 6 characters">
                            @error('password')
                                <span class="cc-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mc-modal-actions" style="margin-top:1.5rem;">
                            <button type="button" class="btn btn-ghost"
                                wire:click="$set('showForm',false)">Cancel</button>
                            <button type="submit" class="btn btn-primary"><span wire:loading.remove
                                    wire:target="save">{{ $editingId ? 'Save Changes' : 'Create Teacher' }}
                                    →</span><span wire:loading wire:target="save">Saving...</span></button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        @if ($deletingId)
            <div class="mc-modal-overlay">
                <div class="mc-modal">
                    <div class="mc-modal-icon">🗑️</div>
                    <h3 class="mc-modal-title">Delete this teacher?</h3>
                    <p class="mc-modal-sub">Their courses and all course data will be permanently deleted.</p>
                    <div class="mc-modal-actions">
                        <button class="btn btn-ghost" wire:click="cancelDelete">Cancel</button>
                        <button class="btn btn-danger" wire:click="delete"><span wire:loading.remove
                                wire:target="delete">Yes, Delete</span><span wire:loading
                                wire:target="delete">Deleting...</span></button>
                    </div>
                </div>
            </div>
        @endif

        <div class="mc-filters"><input type="text" class="mc-search" wire:model.live.debounce.300ms="search"
                placeholder="Search by name or email..."></div>

        <div class="mc-table-wrap">
            <table class="mc-table">
                <thead>
                    <tr>
                        <th>Teacher</th>
                        <th>Email</th>
                        <th>Courses</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                        <tr class="mc-row">
                            <td>
                                <div class="mc-course-name">
                                    <div class="admin-avatar-sm">{{ strtoupper(substr($teacher->name, 0, 2)) }}</div>
                                    <span>{{ $teacher->name }}</span>
                                </div>
                            </td>
                            <td class="mc-desc">{{ $teacher->email }}</td>
                            <td><span class="mc-dept-badge">{{ $teacher->courses_count }} courses</span></td>
                            <td class="mc-date">{{ $teacher->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="mc-actions"><button class="mc-btn-edit"
                                        wire:click="openEdit({{ $teacher->id }})">Edit</button><button
                                        class="mc-btn-delete"
                                        wire:click="confirmDelete({{ $teacher->id }})">Delete</button></div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding:2rem;text-align:center;" class="empty-msg">No teachers
                                found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mc-pagination">{{ $teachers->links() }}</div>
    </main>
</div>
