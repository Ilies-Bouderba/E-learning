<?php

namespace App\Notifications;

use App\Models\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExamReminder extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Exam   $exam,
        private readonly string $timeLabel
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $startTime = $this->exam->start_date->format('F j, Y \a\t g:i A');

        return (new MailMessage)
            ->subject("⏰ Exam Starting in {$this->timeLabel} — {$this->exam->title}")
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("This is a reminder that your exam **{$this->exam->title}** starts in **{$this->timeLabel}**.")
            ->line("**Course:** {$this->exam->course->title}")
            ->line("**Start Time:** {$startTime}")
            ->line("**Duration:** " . ($this->exam->duration_minutes ? $this->exam->duration_minutes . ' minutes' : 'No time limit'))
            ->action('Go to Course', url('/cours/' . $this->exam->course_id))
            ->line('Make sure you are ready before the exam starts. Good luck!')
            ->salutation('The EduMex Team');
    }
}
