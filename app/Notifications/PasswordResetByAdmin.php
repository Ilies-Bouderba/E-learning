<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetByAdmin extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $newPassword
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Password Has Been Reset — EduMex')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your password reset request has been processed by the admin.')
            ->line('Your new password is:')
            ->line('**' . $this->newPassword . '**')
            ->line('Please log in and change your password immediately after signing in.')
            ->action('Log In Now', url('/login'))
            ->line('If you did not request a password reset, please contact support immediately.')
            ->salutation('The EduMex Team');
    }
}
