<?php

namespace App\Events;

use App\Models\ExamAttempt;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExamGraded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly ExamAttempt $attempt
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->attempt->student_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'exam.graded';
    }

    public function broadcastWith(): array
    {
        return [
            'exam_id'     => $this->attempt->exam_id,
            'exam_title'  => $this->attempt->exam->title ?? '',
            'score'       => $this->attempt->total_score,
            'total'       => $this->attempt->exam->total_score ?? 0,
            'course_id'   => $this->attempt->exam->course_id ?? null,
        ];
    }
}
