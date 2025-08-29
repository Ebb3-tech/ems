<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskNotification extends Notification
{
    use Queueable;

    protected $task;
    protected $action;
    protected $actionBy;

    public function __construct(Task $task, $action, $actionBy)
    {
        $this->task = $task;
        $this->action = $action; // 'created', 'updated', 'status_updated'
        $this->actionBy = $actionBy; // User who performed the action
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $subject = $this->getSubject();
        $greeting = 'Hello ' . $notifiable->name . ',';
        
        $message = (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line($this->getActionLine())
            ->line('**Task Details:**')
            ->line('Title: ' . $this->task->title)
            ->line('Description: ' . ($this->task->description ?? 'No description provided'))
            ->line('Priority: ' . ucfirst($this->task->priority))
            ->line('Status: ' . ucfirst(str_replace('_', ' ', $this->task->status)))
            ->line('Assigned By: ' . ($this->task->assignedBy->name ?? 'N/A'))
            ->line('Assigned To: ' . ($this->task->assignedTo->name ?? 'Unassigned'))
            ->line('Department: ' . ($this->task->department->name ?? 'N/A'))
            ->line('Deadline: ' . ($this->task->deadline ? $this->task->deadline->format('Y-m-d H:i') : 'No deadline set'));

        if ($this->action !== 'created') {
            $message->line('Action performed by: ' . $this->actionBy->name);
        }

        $message->action('View Task Details', url('/tasks/' . $this->task->id))
                ->line('Thank you for using Lazizi Management System!');

        return $message;
    }

    private function getSubject()
    {
        switch ($this->action) {
            case 'created':
                return 'New Task Assigned: ' . $this->task->title;
            case 'updated':
                return 'Task Updated: ' . $this->task->title;
            case 'status_updated':
                return 'Task Status Changed: ' . $this->task->title;
            default:
                return 'Task Notification: ' . $this->task->title;
        }
    }

    private function getActionLine()
    {
        switch ($this->action) {
            case 'created':
                return 'A new task has been assigned to you.';
            case 'updated':
                return 'A task assigned to you has been updated.';
            case 'status_updated':
                return 'The status of your task has been changed.';
            default:
                return 'There has been an update to your task.';
        }
    }
}