<?php

namespace App\Services;

use App\Models\ActivityStreak;
use App\Models\Pod;
use App\Models\PodMessage;
use App\Models\Race;
use App\Models\User;
use Carbon\Carbon;

class StreakService
{
    /**
     * Record activity for a user when they complete a race.
     * Updates their streak and awards points to all their active pods.
     * Non-punitive: allows 1 rest day per 7-day window.
     *
     * Returns the updated ActivityStreak.
     */
    public function recordActivity(User $user, Race $race): ActivityStreak
    {
        $streak = ActivityStreak::firstOrCreate(
            ['user_id' => $user->id],
            [
                'current_streak' => 0,
                'longest_streak' => 0,
                'rest_days_used_this_week' => 0,
            ]
        );

        $today = Carbon::today();
        $distanceKm = $this->toKm($race);

        $this->updateStreak($streak, $today);

        $points = $streak->pointsFor($distanceKm);
        $this->awardPointsToPods($user, $race, $streak, $points);

        return $streak;
    }

    /**
     * Recalculate a user's streak from scratch based on their race history.
     * Useful for initial seed or corrections.
     */
    public function recalculate(User $user): ActivityStreak
    {
        $streak = ActivityStreak::firstOrCreate(['user_id' => $user->id]);

        $dates = $user->races()
            ->where('status', 'completed')
            ->orderBy('date')
            ->pluck('date')
            ->unique()
            ->values();

        if ($dates->isEmpty()) {
            $streak->update(['current_streak' => 0, 'longest_streak' => 0]);

            return $streak;
        }

        $current = 0;
        $longest = 0;
        $restUsed = 0;
        $weekStart = null;

        foreach ($dates as $i => $date) {
            if ($i === 0) {
                $current = 1;
                $weekStart = $date->copy()->startOfWeek();

                continue;
            }

            $prev = $dates[$i - 1];
            $diff = $prev->diffInDays($date);

            // Reset rest days counter when entering a new week
            $newWeekStart = $date->copy()->startOfWeek();
            if ($weekStart && $newWeekStart->gt($weekStart)) {
                $weekStart = $newWeekStart;
                $restUsed = 0;
            }

            if ($diff === 1) {
                $current++;
            } elseif ($diff === 2 && $restUsed < 1) {
                // Grace: one rest day allowed per week
                $restUsed++;
                $current++;
            } else {
                $current = 1;
                $restUsed = 0;
            }

            $longest = max($longest, $current);
        }

        $streak->update([
            'current_streak' => $current,
            'longest_streak' => max($longest, $current),
            'last_activity_date' => $dates->last(),
            'week_start_date' => $dates->last()?->copy()->startOfWeek(),
            'rest_days_used_this_week' => $restUsed,
        ]);

        return $streak;
    }

    private function updateStreak(ActivityStreak $streak, Carbon $today): void
    {
        $last = $streak->last_activity_date;

        if (! $last) {
            $streak->update([
                'current_streak' => 1,
                'longest_streak' => max(1, $streak->longest_streak),
                'last_activity_date' => $today,
                'week_start_date' => $today->copy()->startOfWeek(),
                'rest_days_used_this_week' => 0,
            ]);

            return;
        }

        if ($last->isSameDay($today)) {
            // Already recorded today — no change to streak
            return;
        }

        $diff = $last->diffInDays($today);

        // Reset rest-day counter when entering a new week
        $newWeekStart = $today->copy()->startOfWeek();
        $currentWeekStart = $streak->week_start_date ?? $last->copy()->startOfWeek();
        $restUsed = $streak->rest_days_used_this_week;

        if ($newWeekStart->gt($currentWeekStart)) {
            $restUsed = 0;
            $currentWeekStart = $newWeekStart;
        }

        if ($diff === 1) {
            // Consecutive day
            $newStreak = $streak->current_streak + 1;
        } elseif ($diff === 2 && $restUsed < 1) {
            // One rest day grace — non-punitive
            $restUsed++;
            $newStreak = $streak->current_streak + 1;
        } else {
            // Streak broken
            $newStreak = 1;
            $restUsed = 0;
        }

        $streak->update([
            'current_streak' => $newStreak,
            'longest_streak' => max($newStreak, $streak->longest_streak),
            'last_activity_date' => $today,
            'week_start_date' => $currentWeekStart,
            'rest_days_used_this_week' => $restUsed,
        ]);
    }

    private function awardPointsToPods(User $user, Race $race, ActivityStreak $streak, int $points): void
    {
        $activePods = $user->pods()->where('pods.status', 'active')->get();

        foreach ($activePods as $pod) {
            // Add points to pivot
            $pod->members()->updateExistingPivot($user->id, [
                'points' => \DB::raw("points + {$points}"),
            ]);

            // Post celebration message in pod feed
            $distanceKm = $this->toKm($race);
            $multiplierLabel = $streak->current_streak >= 3
                ? " 🔥 ×{$streak->multiplier()}"
                : '';

            PodMessage::create([
                'pod_id' => $pod->id,
                'user_id' => $user->id,
                'type' => 'celebration',
                'message' => sprintf(
                    '%s completó %s km en «%s» y ganó %d puntos%s',
                    $user->name,
                    rtrim(rtrim(number_format($distanceKm, 2, '.', ''), '0'), '.'),
                    $race->name,
                    $points,
                    $multiplierLabel
                ),
            ]);
        }
    }

    private function toKm(Race $race): float
    {
        $distance = (float) $race->distance;

        return $race->distance_unit === 'mi' ? $distance * 1.60934 : $distance;
    }
}
