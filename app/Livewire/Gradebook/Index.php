<?php

namespace App\Livewire\Gradebook;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\ExamAttempt;
use App\Models\QuizAttempt;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
class Index extends Component
{
    public Course $course;
    public string $search = '';

    public function mount(Course $course): void
    {
        if (! auth()->user()->isTeacher() || (int) $course->teacher_id !== (int) auth()->id()) {
            abort(403);
        }
        $this->course = $course;
    }

    public function exportCsv(): StreamedResponse
    {
        $data    = $this->getGradebookData();
        $exams   = $this->course->exams()->orderBy('created_at')->get();
        $quizzes = $this->course->quizzes()->where('is_published', true)->orderBy('created_at')->get();

        return response()->streamDownload(function () use ($data, $exams, $quizzes) {
            $handle = fopen('php://output', 'w');

            $headers = ['Student Name', 'Email', 'Progress %'];
            foreach ($exams   as $e) $headers[] = 'Exam: ' . $e->title;
            foreach ($quizzes as $q) $headers[] = 'Quiz: ' . $q->title;
            $headers[] = 'Average %';
            fputcsv($handle, $headers);

            foreach ($data as $row) {
                $line = [$row['name'], $row['email'], $row['progress'] . '%'];
                foreach ($exams   as $e) $line[] = $row['exams'][$e->id]   ?? 'N/A';
                foreach ($quizzes as $q) $line[] = $row['quizzes'][$q->id] ?? 'N/A';
                $line[] = $row['average'] . '%';
                fputcsv($handle, $line);
            }

            fclose($handle);
        }, 'gradebook_' . $this->course->id . '_' . now()->format('Y-m-d') . '.csv');
    }

    public function getGradebookData(): array
    {
        $enrollments = Enrollment::where('course_id', $this->course->id)
            ->with('student')
            ->when($this->search, fn ($q) => $q->whereHas('student',
                fn ($s) => $s->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
            ))
            ->get();

        $exams   = $this->course->exams()->orderBy('created_at')->get();
        $quizzes = $this->course->quizzes()->where('is_published', true)->orderBy('created_at')->get();

        $rows = [];

        foreach ($enrollments as $enrollment) {
            $student = $enrollment->student;
            if (! $student) continue;

            $examScores  = [];
            $quizScores  = [];
            $allScores   = [];

            foreach ($exams as $exam) {
                $attempt = ExamAttempt::where('student_id', $student->id)
                    ->where('exam_id', $exam->id)
                    ->where('is_graded', true)
                    ->first();

                if ($attempt && (int) $exam->total_score > 0) {
                    $pct = round(($attempt->total_score / $exam->total_score) * 100);
                    $examScores[$exam->id] = $pct . '%';
                    $allScores[] = $pct;
                } else {
                    $examScores[$exam->id] = $attempt ? 'Pending' : '—';
                }
            }

            foreach ($quizzes as $quiz) {
                $attempt = QuizAttempt::where('student_id', $student->id)
                    ->where('quiz_id', $quiz->id)
                    ->whereNotNull('completed_at')
                    ->first();

                if ($attempt) {
                    $pct = (int) round($attempt->score);
                    $quizScores[$quiz->id] = $pct . '%';
                    $allScores[] = $pct;
                } else {
                    $quizScores[$quiz->id] = '—';
                }
            }

            $rows[] = [
                'student_id' => $student->id,
                'name'       => $student->name,
                'email'      => $student->email,
                'progress'   => $enrollment->progress_percentage,
                'exams'      => $examScores,
                'quizzes'    => $quizScores,
                'average'    => count($allScores) > 0 ? round(array_sum($allScores) / count($allScores)) : 0,
            ];
        }

        return $rows;
    }

    public function render()
    {
        return view('livewire.gradebook.index', [
            'rows'    => $this->getGradebookData(),
            'exams'   => $this->course->exams()->orderBy('created_at')->get(),
            'quizzes' => $this->course->quizzes()->where('is_published', true)->orderBy('created_at')->get(),
        ]);
    }
}
