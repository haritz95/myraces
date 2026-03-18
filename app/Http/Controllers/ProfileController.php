<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        $stats = [
            'races' => $user->races()->count(),
            'km' => (int) $user->races()->where('status', 'completed')->sum('distance'),
        ];

        return view('profile.edit', compact('user', 'stats'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's extended profile data (bio, location, physical stats, etc.).
     */
    public function updateProfileData(UpdateUserProfileRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('avatar');

        if ($request->hasFile('avatar')) {
            $existing = $request->user()->profile?->avatar;
            if ($existing && Storage::disk('public')->exists($existing)) {
                Storage::disk('public')->delete($existing);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $data['is_public'] = $request->boolean('is_public');

        $request->user()->profile()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $data
        );

        return Redirect::route('profile.edit')->with('status', 'profile-data-updated');
    }

    /**
     * Update the user's theme preference.
     */
    public function updateTheme(Request $request): RedirectResponse
    {
        $request->validate(['theme' => ['required', 'in:dark,light']]);

        $request->user()->profile()->updateOrCreate(
            ['user_id' => $request->user()->id],
            ['theme' => $request->theme]
        );

        return Redirect::route('profile.edit')->with('status', 'theme-updated');
    }

    /**
     * Save cookie consent preferences for authenticated users.
     */
    public function updateCookieConsent(Request $request): JsonResponse
    {
        $request->validate([
            'functional' => ['required', 'boolean'],
            'analytics' => ['required', 'boolean'],
        ]);

        $request->user()->profile()->updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'cookie_consented_at' => now(),
                'cookie_functional' => $request->boolean('functional'),
                'cookie_analytics' => $request->boolean('analytics'),
            ]
        );

        return response()->json(['ok' => true]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
