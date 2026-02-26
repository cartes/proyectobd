<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AdminNewUserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected User $user) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
            'user_type' => $this->user->user_type,
            'country' => $this->user->country?->name ?? 'Sin país',
            'message' => "Nuevo registro: {$this->user->name} ({$this->user->user_type})",
            'link' => '/admin/moderation/users/'.$this->user->id,
        ];
    }
}
