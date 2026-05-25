<div class="dash-layout">
    <livewire:course-sidebar :course="$course" active="gradebook" />

    <main class="dash-main">
        <div class="dash-header">
            <div>
                <h1 class="dash-title">Grade Book</h1>
                <p class="dash-subtitle">{{ $course->title }} — all student scores</p>
            </div>
            <div style="display:flex;gap:0.75rem;align-items:center;">
                <input type="text" wire:model.live="search" placeholder="Search students..." class="form-control" style="max-width:220px;">
                <button wire:click="exportCsv" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Export CSV
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="mc-flash">{{ session('success') }}</div>
        @endif

        <div class="dash-card" style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:0.85rem;">
                <thead>
                    <tr style="border-bottom:var(--border);">
                        <th style="text-align:left;padding:0.75rem 1rem;font-family:var(--font-head);font-weight:700;white-space:nowrap;">Student</th>
                        <th style="text-align:center;padding:0.75rem 0.5rem;font-family:var(--font-head);font-weight:700;white-space:nowrap;">Progress</th>
                        @foreach($exams as $exam)
                            <th style="text-align:center;padding:0.75rem 0.5rem;font-family:var(--font-head);font-weight:700;white-space:nowrap;max-width:120px;">
                                <div style="font-size:0.7rem;color:var(--c-muted);font-weight:600;">EXAM</div>
                                {{ Str::limit($exam->title, 16) }}
                            </th>
                        @endforeach
                        @foreach($quizzes as $quiz)
                            <th style="text-align:center;padding:0.75rem 0.5rem;font-family:var(--font-head);font-weight:700;white-space:nowrap;max-width:120px;">
                                <div style="font-size:0.7rem;color:var(--c-muted);font-weight:600;">QUIZ</div>
                                {{ Str::limit($quiz->title, 16) }}
                            </th>
                        @endforeach
                        <th style="text-align:center;padding:0.75rem 1rem;font-family:var(--font-head);font-weight:700;white-space:nowrap;">Average</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr style="border-bottom:1px solid rgba(15,14,23,0.07);transition:background 0.1s;" onmouseover="this.style.background='rgba(15,14,23,0.03)'" onmouseout="this.style.background=''">
                            <td style="padding:0.875rem 1rem;">
                                <div style="display:flex;align-items:center;gap:0.625rem;">
                                    <div style="width:32px;height:32px;border-radius:50%;background:var(--c-dark);color:var(--c-yellow);display:flex;align-items:center;justify-content:center;font-family:var(--font-head);font-weight:800;font-size:0.75rem;flex-shrink:0;">
                                        {{ strtoupper(substr($row['name'], 0, 2)) }}
                                    </div>
                                    <div>
                                        <div style="font-family:var(--font-head);font-weight:700;font-size:0.85rem;">{{ $row['name'] }}</div>
                                        <div style="font-size:0.7rem;color:var(--c-muted);">{{ $row['email'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align:center;padding:0.875rem 0.5rem;">
                                <div style="display:flex;flex-direction:column;align-items:center;gap:3px;">
                                    <span style="font-family:var(--font-head);font-weight:700;font-size:0.85rem;">{{ $row['progress'] }}%</span>
                                    <div style="width:60px;height:4px;background:rgba(15,14,23,0.1);border-radius:2px;">
                                        <div style="width:{{ $row['progress'] }}%;height:100%;background:var(--c-yellow);border-radius:2px;"></div>
                                    </div>
                                </div>
                            </td>
                            @foreach($exams as $exam)
                                @php $score = $row['exams'][$exam->id] ?? '—'; @endphp
                                <td style="text-align:center;padding:0.875rem 0.5rem;">
                                    <span style="font-family:var(--font-head);font-weight:700;font-size:0.85rem;
                                        color:{{ str_contains($score, '%') ? (intval($score) >= 70 ? '#10b981' : (intval($score) >= 50 ? '#f59e0b' : '#ef4444')) : 'var(--c-muted)' }}">
                                        {{ $score }}
                                    </span>
                                </td>
                            @endforeach
                            @foreach($quizzes as $quiz)
                                @php $score = $row['quizzes'][$quiz->id] ?? '—'; @endphp
                                <td style="text-align:center;padding:0.875rem 0.5rem;">
                                    <span style="font-family:var(--font-head);font-weight:700;font-size:0.85rem;
                                        color:{{ str_contains($score, '%') ? (intval($score) >= 70 ? '#10b981' : (intval($score) >= 50 ? '#f59e0b' : '#ef4444')) : 'var(--c-muted)' }}">
                                        {{ $score }}
                                    </span>
                                </td>
                            @endforeach
                            <td style="text-align:center;padding:0.875rem 1rem;">
                                <span class="badge" style="background:{{ $row['average'] >= 70 ? '#10b981' : ($row['average'] >= 50 ? '#f59e0b' : '#ef4444') }};color:white;font-size:0.8rem;">
                                    {{ $row['average'] }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="99" style="text-align:center;padding:3rem;color:var(--c-muted);">No students enrolled yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</div>
