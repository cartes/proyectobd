<?php

namespace App\Services;

use App\Mail\NewLikeEmail;
use App\Mail\WeeklyStatsEmail;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Send a notification for a new like if the user is active or premium.
     */
    public function notifyNewLike(User $recipient, User $liker)
    {
        if (! $this->shouldNotify($recipient)) {
            logger()->info("Skipping notification for inactive user #{$recipient->id}");

            return;
        }

        $token = Crypt::encryptString($recipient->id);
        $trackingUrl = route('engagement.track', ['token' => $token]);

        Mail::to($recipient->email)->queue(new NewLikeEmail($recipient, $liker, $trackingUrl));

        logger()->info("New like notification queued for user #{$recipient->id}");
    }

    /**
     * Send weekly statistics summary.
     */
    public function sendWeeklyStats(User $user, array $stats)
    {
        if (! $this->shouldNotify($user, true)) {
            return;
        }

        $token = Crypt::encryptString($user->id);
        $trackingUrl = route('engagement.track', ['token' => $token]);

        Mail::to($user->email)->queue(new WeeklyStatsEmail($user, $stats, $trackingUrl));
    }

    /**
     * Logic to determine if a user should receive notifications.
     *
     * @param  bool  $isWeekly  Whether it's the weekly summary (more strict for inactive)
     */
    private function shouldNotify(User $user, bool $isWeekly = false): bool
    {
        // Premium users always get notifications
        if ($user->is_premium) {
            return true;
        }

        // Active users (interacted in last 30 days or high score)
        $isRecentInteracter = $user->last_email_interaction_at && $user->last_email_interaction_at->diffInDays(now()) < 30;

        if ($isWeekly) {
            // For weekly stats, we only want active or premium users to avoid spamming truly dead accounts
            return $isRecentInteracter || $user->engagement_score > 50;
        }

        // For real-time likes, we are slightly more lenient
        return $isRecentInteracter || $user->engagement_score >= 10 || $user->created_at->diffInDays(now()) < 7;
    }
}
