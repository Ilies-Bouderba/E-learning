<?php

namespace App\Console\Commands;

use App\Models\Enrollment;
use App\Models\Exam;
use App\Notifications\ExamReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class SendExamReminders extends Command
{
    protected $signature   = 'exams:send-reminders';
    protected $description = 'Send email reminders to enrolled students for upcoming exams';


    private array $windows = [
        '1 day'      => 1440,
        '3 hours'    => 180,
        '1 hour'     => 60,
        '10 minutes' => 10,
    ];

    private int $toleranceMinutes = 5;

    public function handle(): int
    {
        $now = Carbon::now();

        foreach ($this->windows as $label => $minutesBefore) {
            $windowStart = $now->copy()->addMinutes($minutesBefore - $this->toleranceMinutes);
            $windowEnd   = $now->copy()->addMinutes($minutesBefore + $this->toleranceMinutes);

            $exams = Exam::with(['course'])
                ->where('is_published', true)
                ->whereNotNull('start_date')
                ->whereBetween('start_date', [$windowStart, $windowEnd])
                ->get();

            foreach ($exams as $exam) {
                $enrollments = Enrollment::where('course_id', $exam->course_id)
                    ->with('student')
                    ->get();

                foreach ($enrollments as $enrollment) {
                    $student = $enrollment->student;

                    if (! $student) continue;

                    $cacheKey = "exam_reminder_{$exam->id}_{$student->id}_{$minutesBefore}";

                    if (Cache::has($cacheKey)) continue;

                    try {
                        $student->notify(new ExamReminder($exam, $label));
                        Cache::put($cacheKey, true, $this->toleranceMinutes * 3);
                        $this->info("Sent {$label} reminder to {$student->email} for exam: {$exam->title}");
                    } catch (\Throwable $e) {
                        $this->error("Failed to send reminder to {$student->email}: " . $e->getMessage());
                    }
                }
            }
        }

        $this->info('Exam reminders processed at ' . $now->toDateTimeString());
        return Command::SUCCESS;
    }
}
