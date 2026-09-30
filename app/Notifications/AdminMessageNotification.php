<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminMessageNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $subjectLine,
        public string $messageBody,
        public ?string $actionUrl = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'admin_message',
            'title' => $this->subjectLine,
            'message' => $this->messageBody,
            'link' => $this->actionUrl ?? route('dashboard'),
            'created_at' => now()->toIso8601String(),
        ];
    }
}
