<?php

namespace App\Http\Controllers;

use App\Models\Race;
use App\Models\SocialAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class StravaImportController extends Controller
{
    private const STRAVA_API = 'https://www.strava.com/api/v3';

    private const SPORT_TYPE_MAP = [
        'Run' => 'road',
        'TrailRun' => 'trail',
        'VirtualRun' => 'road',
        'Walk' => 'road',
        'Hike' => 'trail',
    ];

    public function index(Request $request): View|RedirectResponse
    {
        $stravaAccount = $this->stravaAccount();

        if (! $stravaAccount) {
            return view('strava.connect');
        }

        $token = $this->freshToken($stravaAccount);

        if (! $token) {
            return redirect()->route('social.redirect', 'strava')
                ->with('info', 'Tu sesión de Strava ha caducado. Vuelve a conectar.');
        }

        $page = (int) $request->get('page', 1);

        $response = Http::withToken($token)
            ->get(self::STRAVA_API.'/athlete/activities', [
                'per_page' => 20,
                'page' => $page,
            ]);

        if (! $response->successful()) {
            return redirect()->route('strava.import')
                ->with('error', 'No se pudieron cargar las actividades de Strava.');
        }

        $activities = collect($response->json())
            ->filter(fn ($a) => isset(self::SPORT_TYPE_MAP[$a['sport_type'] ?? $a['type'] ?? ''])
                || str_contains(strtolower($a['sport_type'] ?? $a['type'] ?? ''), 'run'));

        $imported = Race::where('user_id', auth()->id())
            ->whereNotNull('strava_id')
            ->pluck('strava_id')
            ->toArray();

        return view('strava.index', compact('activities', 'imported', 'page'));
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'activities' => ['required', 'array', 'min:1', 'max:50'],
            'activities.*' => ['required', 'array'],
        ]);

        if (! $this->stravaAccount()) {
            return redirect()->route('strava.import');
        }

        $user = $request->user();
        $count = 0;

        foreach ($request->input('activities') as $data) {
            $stravaId = (int) ($data['id'] ?? 0);

            if (! $stravaId || Race::where('user_id', $user->id)->where('strava_id', $stravaId)->exists()) {
                continue;
            }

            $distanceKm = round((float) ($data['distance'] ?? 0) / 1000, 3);

            Race::create([
                'user_id' => $user->id,
                'strava_id' => $stravaId,
                'name' => $data['name'] ?? 'Actividad Strava',
                'date' => substr($data['start_date_local'] ?? now()->toDateString(), 0, 10),
                'distance' => $distanceKm,
                'distance_unit' => 'km',
                'modality' => self::SPORT_TYPE_MAP[$data['sport_type'] ?? $data['type'] ?? ''] ?? 'road',
                'finish_time' => (int) ($data['moving_time'] ?? 0) ?: null,
                'status' => 'completed',
                'location' => $data['location_city'] ?? null,
                'country' => $data['location_country'] ?? null,
                'notes' => $this->buildNotes($data),
                'is_public' => false,
            ]);

            $count++;
        }

        return redirect()->route('races.index')
            ->with('success', "{$count} ".($count === 1 ? 'actividad importada' : 'actividades importadas').' desde Strava.');
    }

    private function stravaAccount(): ?SocialAccount
    {
        return SocialAccount::where('user_id', auth()->id())
            ->where('provider', 'strava')
            ->first();
    }

    private function freshToken(SocialAccount $account): ?string
    {
        if (! $account->token_expires_at || $account->token_expires_at->isFuture()) {
            return $account->token;
        }

        if (! $account->refresh_token) {
            return null;
        }

        $response = Http::asForm()->post('https://www.strava.com/oauth/token', [
            'client_id' => config('services.strava.client_id'),
            'client_secret' => config('services.strava.client_secret'),
            'refresh_token' => $account->refresh_token,
            'grant_type' => 'refresh_token',
        ]);

        if (! $response->successful()) {
            return null;
        }

        $data = $response->json();

        $account->update([
            'token' => $data['access_token'],
            'refresh_token' => $data['refresh_token'] ?? $account->refresh_token,
            'token_expires_at' => isset($data['expires_at'])
                ? now()->setTimestamp((int) $data['expires_at'])
                : null,
        ]);

        return $data['access_token'];
    }

    /** @param array<string,mixed> $data */
    private function buildNotes(array $data): ?string
    {
        $parts = [];

        if (! empty($data['total_elevation_gain'])) {
            $parts[] = 'Desnivel: '.\round((float) $data['total_elevation_gain']).' m';
        }

        if (! empty($data['average_heartrate'])) {
            $parts[] = 'FC media: '.\round((float) $data['average_heartrate']).' ppm';
        }

        if (! empty($data['description'])) {
            $parts[] = $data['description'];
        }

        return $parts ? implode(' · ', $parts) : null;
    }
}
