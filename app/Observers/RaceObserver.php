<?php

namespace App\Observers;

use App\Models\Race;
use App\Services\StreakService;

class RaceObserver
{
    public function __construct(private readonly StreakService $streakService) {}

    public function created(Race $race): void
    {
        if ($race->status === 'completed') {
            $this->streakService->recordActivity($race->user, $race);
        }
    }

    public function updated(Race $race): void
    {
        // Only trigger when a race transitions to 'completed'
        if ($race->wasChanged('status') && $race->status === 'completed') {
            $this->streakService->recordActivity($race->user, $race);
        }
    }
}
