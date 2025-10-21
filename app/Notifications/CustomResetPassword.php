<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPassword extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->markdown('emails.custom-reset-password', [
                'token' => $this->token,
                'user' => $notifiable
            ])
            ->subject('Reset Your Password - Mega Market');
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
