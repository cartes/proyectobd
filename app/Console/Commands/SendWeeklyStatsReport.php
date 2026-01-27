<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendWeeklyStatsReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:send-weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly statistics report to active users';

    /**
     * Execute the console command.
     */
    public function handle(NotificationService $notificationService)
    {
        $this->info('Starting weekly stats report...');

        $users = User::where('is_active', true)->get();
        $sentCount = 0;

        foreach ($users as $user) {
            // Calcular estadísticas de la última semana
            $stats = $this->calculateWeeklyStats($user);

            // Enviar email (el servicio ya filtra por engagement)
            $notificationService->sendWeeklyStats($user, $stats);
            $sentCount++;
        }

        $this->info("Weekly stats sent to {$sentCount} users.");

        return Command::SUCCESS;
    }

    /**
     * Calculate weekly statistics for a user
     */
    private function calculateWeeklyStats(User $user): array
    {
        $weekAgo = now()->subWeek();

        // Nuevos likes recibidos
        $newLikes = DB::table('likes')
            ->where('liked_user_id', $user->id)
            ->where('created_at', '>=', $weekAgo)
            ->count();

        // Visitas al perfil
        $profileViews = DB::table('profile_views')
            ->where('viewed_id', $user->id)
            ->where('created_at', '>=', $weekAgo)
            ->count();

        // Nuevos mensajes recibidos
        $newMessages = DB::table('messages')
            ->where('receiver_id', $user->id)
            ->where('created_at', '>=', $weekAgo)
            ->count();

        return [
            'likes' => $newLikes,
            'views' => $profileViews,
            'messages' => $newMessages,
        ];
    }
}
