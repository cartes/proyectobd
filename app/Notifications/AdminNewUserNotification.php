<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNewUserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected User $user) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $userTypeLabel = match ($this->user->user_type) {
            'sugar_daddy' => '💰 Sugar Daddy',
            'sugar_baby' => '🍬 Sugar Baby',
            default => ucfirst($this->user->user_type),
        };

        $country = $this->user->country?->name ?? 'No especificado';
        $city = $this->user->city ?? 'No especificada';
        $registeredAt = $this->user->created_at->format('d/m/Y H:i');

        return (new MailMessage)
            ->subject('🎉 Nuevo registro en Big-Dad')
            ->greeting('¡Hola, Admin!')
            ->line('Se ha registrado un nuevo usuario en Big-Dad.')
            ->line("**Tipo:** {$userTypeLabel}")
            ->line("**Nombre:** {$this->user->name}")
            ->line("**Email:** {$this->user->email}")
            ->line("**País:** {$country}")
            ->line("**Ciudad:** {$city}")
            ->line("**Fecha de registro:** {$registeredAt}")
            ->action('Ver perfil en el panel', url('/admin/moderation/users/'.$this->user->id))
            ->salutation('Big-Dad · Sistema de notificaciones');
    }
}
