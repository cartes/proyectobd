<?php

namespace App\Services;

use App\Models\BlockedWord;
use App\Models\Message;
use App\Models\Report;
use App\Models\User;
use App\Models\UserAction;
use Illuminate\Support\Str;

class ModerationService
{
    private array $leetMap = [
        '4' => 'a',
        '@' => 'a',
        '3' => 'e',
        '1' => 'i',
        '!' => 'i',
        '|' => 'i',
        '0' => 'o',
        '$' => 's',
        '7' => 't',
        '2' => 'z',
        '5' => 's',
        '8' => 'b',
        '9' => 'g',
        '6' => 'g',
        'x' => 'x',
        '+' => 't',
    ];

    /**
     * Verificar si un texto contiene contenido inapropiado
     */
    public function scanMessage(string $text): array
    {
        $normalized = $this->normalize($text);
        $blockedWords = BlockedWord::active()->get();
        $flaggedWords = [];
        $totalSeverity = 0;

        foreach ($blockedWords as $word) {
            if ($this->containsWord($normalized, $word->word)) {
                $flaggedWords[] = [
                    'word' => $word->word,
                    'severity' => $word->severity,
                ];
                $totalSeverity += $this->severityScore($word->severity);
            }
        }

        return [
            'is_flagged' => !empty($flaggedWords),
            'flagged_words' => $flaggedWords,
            'severity_level' => $this->determineSeverity($totalSeverity),
            'total_severity' => $totalSeverity,
        ];
    }

    /**
     * Enmascarar palabras inapropiadas
     */
    public function maskProfanity(string $text): string
    {
        $blockedWords = BlockedWord::active()->pluck('word')->toArray();

        foreach ($blockedWords as $word) {
            $pattern = '/\b' . preg_quote($word, '/') . '\b/iu';
            $replacement = str_repeat('*', strlen($word));
            $text = preg_replace($pattern, $replacement, $text);
        }

        return $text;
    }

    /**
     * Procesar mensaje flagged
     */
    public function processMessage(Message $message, array $scanResult)
    {
        $severity = $scanResult['severity_level'];

        // Actualizar mensaje
        $message->update([
            'is_flagged' => true,
            'flagged_reason' => implode(', ', array_column($scanResult['flagged_words'], 'word')),
            'content' => $severity === 'high'
                ? $this->maskProfanity($message->content)
                : $message->content,
        ]);

        // Tomar acciones según severidad
        if ($severity === 'high') {
            $this->recordViolation($message->sender_id, 'profanity', 'high');
        }

        return $message;
    }

    /**
     * Registrar violación
     */
    public function recordViolation(int $userId, string $reason, string $severity = 'medium')
    {
        $user = User::find($userId);
        $violationCount = UserAction::where('user_id', $userId)
            ->where('action_type', 'warning')
            ->where('is_active', true)
            ->count();

        // Escalado de acciones
        if ($violationCount >= 3) {
            // Ban de 7 días
            $this->suspendUser($userId, 7, $reason);
        } elseif ($violationCount >= 2) {
            // Warn again
            $this->warnUser($userId, $reason);
        } else {
            // First warn
            $this->warnUser($userId, $reason);
        }
    }

    /**
     * Advertir usuario
     */
    public function warnUser(int $userId, string $reason)
    {
        UserAction::create([
            'user_id' => $userId,
            'action_type' => 'warning',
            'reason' => $reason,
        ]);

        // Notificar al usuario
        $user = User::find($userId);
        if ($user) {
            // Aquí puedes enviar un email o notificación
            \Log::warning("User {$userId} warned for: {$reason}");
        }
    }

    /**
     * Suspender usuario temporalmente
     */
    public function suspendUser(int $userId, int $days, string $reason)
    {
        // Desactivar acciones previas
        UserAction::where('user_id', $userId)
            ->where('action_type', 'warning')
            ->where('is_active', true)
            ->update(['is_active' => false]);

        UserAction::create([
            'user_id' => $userId,
            'action_type' => 'suspension',
            'reason' => $reason,
            'expires_at' => now()->addDays($days),
        ]);

        \Log::warning("User {$userId} suspended for {$days} days. Reason: {$reason}");
    }

    /**
     * Banear usuario permanentemente
     */
    public function banUser(int $userId, string $reason, int $adminId = null)
    {
        // Desactivar todas las acciones previas
        UserAction::where('user_id', $userId)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        UserAction::create([
            'user_id' => $userId,
            'action_type' => 'ban',
            'reason' => $reason,
            'initiated_by' => $adminId,
        ]);

        // Desactivar usuario
        $user = User::find($userId);
        if ($user) {
            $user->update(['is_active' => false]);
        }

        \Log::error("User {$userId} banned. Reason: {$reason}");
    }

    /**
     * Crear reporte
     */
    public function createReport(int $reporterId, int $reportedUserId, string $type, $id, string $reason, string $description = null): Report
    {
        return Report::create([
            'reporter_id' => $reporterId,
            'reported_user_id' => $reportedUserId,
            'type' => $type,
            'message_id' => $type === 'message' ? $id : null,
            'conversation_id' => $type === 'conversation' ? $id : null,
            'reason' => $reason,
            'description' => $description,
            'status' => 'pending',
        ]);
    }

    /**
     * Métodos privados auxiliares
     */
    private function normalize(string $text): string
    {
        $text = strtolower($text);
        $text = strtr($text, $this->leetMap);

        // Eliminar caracteres no alfanuméricos (espacios, puntos, etc. entre letras)
        $text = preg_replace('/[^a-z0-9]/', '', $text);

        // ✅ ELIMINAR CARACTERES REPETIDOS (evita bypass como "p.aaaa.l.4.b.r.4")
        $text = preg_replace('/(.)\1+/', '$1', $text);

        return $text;
    }

    private function containsWord(string $text, string $word): bool
    {
        $normalized = $this->normalize($word);
        return stripos($text, $normalized) !== false;
    }

    private function severityScore(string $severity): int
    {
        return match ($severity) {
            'low' => 1,
            'medium' => 3,
            'high' => 5,
            default => 0,
        };
    }

    private function determineSeverity(int $score): string
    {
        if ($score >= 5)
            return 'high';
        if ($score >= 3)
            return 'medium';
        return 'low';
    }
}
