<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\AdClick;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AdClickController extends Controller
{
    public function click(Request $request, Ad $ad): RedirectResponse
    {
        if ($ad->status !== 'approved') {
            return redirect($ad->target_url);
        }

        $ipHash = hash('sha256', $request->ip());
        $key = "ad_click:{$ad->id}:{$ipHash}";

        if (! RateLimiter::tooManyAttempts($key, 3)) {
            RateLimiter::hit($key, 3600);

            AdClick::create([
                'ad_id' => $ad->id,
                'user_id' => $request->user()?->id,
                'ip_hash' => $ipHash,
                'user_agent' => mb_substr($request->userAgent() ?? '', 0, 300),
                'created_at' => now(),
            ]);

            Ad::where('id', $ad->id)->increment('clicks_count');
        }

        return redirect($ad->target_url);
    }
}
