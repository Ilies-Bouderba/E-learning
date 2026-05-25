<?php

namespace App\Notifications;

use App\Models\ExamAttempt;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ExamGradedNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public function __construct(
        private readonly ExamAttempt $attempt
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'       => 'exam_graded',
            'title'      => 'Exam Graded: ' . ($this->attempt->exam->title ?? 'Exam'),
            'score'      => $this->attempt->total_score,
            'total'      => $this->attempt->exam->total_score ?? 0,
            'course_id'  => $this->attempt->exam->course_id ?? null,
            'exam_id'    => $this->attempt->exam_id,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type'      => 'exam_graded',
            'title'     => 'Exam Graded: ' . ($this->attempt->exam->title ?? 'Exam'),
            'score'     => $this->attempt->total_score,
            'total'     => $this->attempt->exam->total_score ?? 0,
            'course_id' => $this->attempt->exam->course_id ?? null,
        ]);
    }
}
