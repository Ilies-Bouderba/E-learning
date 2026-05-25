<?php

namespace App\Livewire\Gradebook;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\ExamAttempt;
use App\Models\QuizAttempt;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Leaderboard extends Component
{
    public Course $course;

    public function mount(Course $course): mixed
    {
        $user = auth()->user();

        if ($user->isStudent()) {
            if (! $course->enrollments()->where('student_id', $user->id)->exists()) {
                abort(403);
            }
        } elseif ($user->isTeacher()) {
            if ((int) $course->teacher_id !== (int) $user->id) {
                abort(403);
            }
        } else {
            abort(403);
        }

        $this->course = $course;
        return null;
    }

    public function render()
    {
        $enrollments = Enrollment::where('course_id', $this->course->id)
            ->with('student')
            ->get();

        $exams   = $this->course->exams()->where('is_published', true)->get();
        $quizzes = $this->course->quizzes()->where('is_published', true)->get();

        $leaderboard = [];

        foreach ($enrollments as $enrollment) {
            $student = $enrollment->student;
            if (! $student) continue;

            $scores = [];

            foreach ($exams as $exam) {
                $attempt = ExamAttempt::where('student_id', $student->id)
                    ->where('exam_id', $exam->id)
                    ->where('is_graded', true)
                    ->first();

                if ($attempt && (int) $exam->total_score > 0) {
                    $scores[] = round(($attempt->total_score / $exam->total_score) * 100);
                }
            }

            foreach ($quizzes as $quiz) {
                $attempt = QuizAttempt::where('student_id', $student->id)
                    ->where('quiz_id', $quiz->id)
                    ->whereNotNull('completed_at')
                    ->first();

                if ($attempt) {
                    $scores[] = (int) round($attempt->score);
                }
            }

            $average = count($scores) > 0 ? round(array_sum($scores) / count($scores)) : 0;

            $leaderboard[] = [
                'student_id' => $student->id,
                'name'       => $student->name,
                'average'    => $average,
                'progress'   => $enrollment->progress_percentage,
                'completed'  => count($scores),
                'is_me'      => (int) $student->id === (int) auth()->id(),
            ];
        }

        usort($leaderboard, fn ($a, $b) => $b['average'] <=> $a['average']);

        foreach ($leaderboard as $i => &$entry) {
            $entry['rank'] = $i + 1;
        }

        $myRank = collect($leaderboard)->firstWhere('is_me', true);

        return view('livewire.gradebook.leaderboard', [
            'leaderboard' => $leaderboard,
            'myRank'      => $myRank,
            'totalExams'  => $exams->count(),
            'totalQuizzes'=> $quizzes->count(),
        ]);
    }
}
