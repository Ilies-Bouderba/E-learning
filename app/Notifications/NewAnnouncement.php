<?php

namespace App\Notifications;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewAnnouncement extends Notification implements ShouldBroadcast
{
    use Queueable;

    public function __construct(
        private readonly Announcement $announcement
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'         => 'announcement',
            'title'        => $this->announcement->title,
            'course_title' => $this->announcement->course->title ?? '',
            'course_id'    => $this->announcement->course_id,
            'announcement_id' => $this->announcement->id,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type'         => 'announcement',
            'title'        => $this->announcement->title,
            'course_title' => $this->announcement->course->title ?? '',
            'course_id'    => $this->announcement->course_id,
        ]);
    }
}
