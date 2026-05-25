<div class="dash-layout">
    <livewire:course-sidebar :course="$course" active="leaderboard" />

    <main class="dash-main">
        <div class="dash-header">
            <div>
                <h1 class="dash-title">Leaderboard</h1>
                <p class="dash-subtitle">{{ $course->title }} — ranked by average score across {{ $totalExams }} exam(s) and {{ $totalQuizzes }} quiz(zes)</p>
            </div>
        </div>

        @if($myRank && auth()->user()->isStudent())
            <div class="dash-card" style="margin-bottom:1.5rem;background:rgba(255,225,77,0.15);border-color:var(--c-yellow);">
                <div style="display:flex;align-items:center;gap:1rem;padding:0.5rem;">
                    <div style="font-size:2rem;font-weight:800;font-family:var(--font-head);color:var(--c-dark);min-width:48px;text-align:center;">#{{ $myRank['rank'] }}</div>
                    <div>
                        <div style="font-family:var(--font-head);font-weight:800;">Your Ranking</div>
                        <div style="font-size:0.85rem;color:var(--c-muted);">Average: {{ $myRank['average'] }}% · Progress: {{ $myRank['progress'] }}%</div>
                    </div>
                </div>
            </div>
        @endif

        <div class="dash-card">
            @forelse($leaderboard as $entry)
                <div style="display:flex;align-items:center;gap:1rem;padding:0.875rem 1rem;border-bottom:1px solid rgba(15,14,23,0.07);
                    {{ $entry['is_me'] ? 'background:rgba(255,225,77,0.1);' : '' }}">

                    <div style="font-family:var(--font-head);font-weight:800;font-size:1rem;min-width:36px;text-align:center;
                        color:{{ $entry['rank'] === 1 ? '#f59e0b' : ($entry['rank'] === 2 ? '#9ca3af' : ($entry['rank'] === 3 ? '#b45309' : 'var(--c-muted)')) }}">
                        @if($entry['rank'] <= 3)
                            {{ ['🥇','🥈','🥉'][$entry['rank']-1] }}
                        @else
                            #{{ $entry['rank'] }}
                        @endif
                    </div>

                    <div style="width:36px;height:36px;border-radius:50%;background:var(--c-dark);color:var(--c-yellow);display:flex;align-items:center;justify-content:center;font-family:var(--font-head);font-weight:800;font-size:0.8rem;flex-shrink:0;">
                        {{ strtoupper(substr($entry['name'], 0, 2)) }}
                    </div>

                    <div style="flex:1;min-width:0;">
                        <div style="font-family:var(--font-head);font-weight:700;font-size:0.9rem;">
                            {{ $entry['is_me'] ? 'You' : $entry['name'] }}
                            @if($entry['is_me'])<span style="font-size:0.7rem;background:var(--c-yellow);color:var(--c-dark);padding:1px 6px;border-radius:999px;margin-left:4px;">YOU</span>@endif
                        </div>
                        <div style="font-size:0.75rem;color:var(--c-muted);">{{ $entry['completed'] }} assessment(s) completed</div>
                    </div>

                    <div style="width:100px;display:flex;flex-direction:column;gap:3px;align-items:flex-end;">
                        <span style="font-family:var(--font-head);font-weight:700;font-size:0.85rem;">{{ $entry['average'] }}%</span>
                        <div style="width:100%;height:5px;background:rgba(15,14,23,0.1);border-radius:3px;">
                            <div style="width:{{ $entry['average'] }}%;height:100%;background:{{ $entry['rank'] === 1 ? '#f59e0b' : 'var(--c-dark)' }};border-radius:3px;transition:width 0.5s;"></div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="empty-msg" style="padding:3rem;">No scores yet — complete exams and quizzes to appear here.</p>
            @endforelse
        </div>
    </main>
</div>
