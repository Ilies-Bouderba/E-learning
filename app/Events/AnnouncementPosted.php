<?php

namespace App\Events;

use App\Models\Announcement;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnnouncementPosted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Announcement $announcement
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('course.' . $this->announcement->course_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'announcement.posted';
    }

    public function broadcastWith(): array
    {
        return [
            'id'          => $this->announcement->id,
            'title'       => $this->announcement->title,
            'course_id'   => $this->announcement->course_id,
            'course_title'=> $this->announcement->course->title ?? '',
            'posted_at'   => $this->announcement->posted_at?->toISOString(),
        ];
    }
}
