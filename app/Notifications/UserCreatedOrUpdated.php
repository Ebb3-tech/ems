<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserCreatedOrUpdated extends Notification
{
    use Queueable;

    protected $action;
    protected $password;

    public function __construct($action, $password = null)
    {
        $this->action = $action; // 'created' or 'updated'
        $this->password = $password;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $subject = $this->action === 'created'
            ? 'Welcome to Lazizi Management System'
            : 'Your Account Has Been Updated';

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line($this->action === 'created'
                ? 'Your account has been created successfully.'
                : 'Your account information has been updated.')
            ->line('Name: ' . $notifiable->name)
            ->line('Email: ' . $notifiable->email)
            ->line('Department: ' . ($notifiable->department->name ?? 'N/A'))
            ->line('Position: ' . ($notifiable->position ?? 'N/A'))
            ->line('Role: ' . $notifiable->role);

        if ($this->action === 'created' && $this->password) {
            $message->line('Password: ' . $this->password);
        }

        $message->line('Thanks for using our system!');

        return $message;
    }
}
