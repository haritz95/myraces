<?php

use App\Http\Controllers\AdClickController;
use App\Http\Controllers\Admin\AdController as AdminAdController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\NavItemController;
use App\Http\Controllers\Admin\PodController as AdminPodController;
use App\Http\Controllers\Admin\RaceEventController as AdminRaceEventController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GearController;
use App\Http\Controllers\MyAdsController;
use App\Http\Controllers\OfflineController;
use App\Http\Controllers\PersonalRecordController;
use App\Http\Controllers\PodController;
use App\Http\Controllers\PremiumController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\RaceCoachController;
use App\Http\Controllers\RaceController;
use App\Http\Controllers\RaceEventController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\StravaImportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/offline', OfflineController::class)->name('offline');

Route::get('/language/{locale}', function (string $locale) {
    if (in_array($locale, ['es', 'en'])) {
        session(['locale' => $locale]);
    }

    return redirect()->back();
})->name('language.switch');

Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');

Route::middleware(['auth', 'verified', 'nav.access'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('races', RaceController::class);

    Route::get('/stats', [StatsController::class, 'index'])->name('stats.index');

    Route::resource('expenses', ExpenseController::class)->except(['show']);

    Route::get('/personal-records', [PersonalRecordController::class, 'index'])->name('personal-records.index');
    Route::post('/personal-records', [PersonalRecordController::class, 'store'])->name('personal-records.store');
    Route::delete('/personal-records/{personalRecord}', [PersonalRecordController::class, 'destroy'])->name('personal-records.destroy');

    Route::resource('gear', GearController::class)->except(['show']);

    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');

    Route::get('/pods', [PodController::class, 'index'])->name('pods.index');
    Route::get('/pods/create', [PodController::class, 'create'])->name('pods.create');
    Route::post('/pods', [PodController::class, 'store'])->name('pods.store');
    Route::get('/pods/{pod}', [PodController::class, 'show'])->name('pods.show');
    Route::post('/pods/{pod}/join', [PodController::class, 'join'])->name('pods.join');
    Route::delete('/pods/{pod}/leave', [PodController::class, 'leave'])->name('pods.leave');
    Route::post('/pods/{pod}/messages', [PodController::class, 'sendMessage'])->name('pods.messages.store');
    Route::get('/pods/{pod}/messages', [PodController::class, 'messages'])->name('pods.messages');

    Route::get('/events', [RaceEventController::class, 'index'])->name('events.index');
    Route::get('/events/{raceEvent:slug}', [RaceEventController::class, 'show'])->name('events.show');
    Route::post('/events/{raceEvent}/attend', [RaceEventController::class, 'toggleAttend'])->name('events.attend');

    Route::get('/premium', [PremiumController::class, 'index'])->name('premium');

    Route::middleware('premium')->group(function () {
        Route::get('/coach', [RaceCoachController::class, 'index'])->name('coach.index');
        Route::post('/coach/chat', [RaceCoachController::class, 'chat'])->name('coach.chat');
    });

    Route::get('/strava/import', [StravaImportController::class, 'index'])->name('strava.import');
    Route::post('/strava/import', [StravaImportController::class, 'import'])->name('strava.import.store');

    Route::get('/ads', [MyAdsController::class, 'index'])->name('my-ads.index');
    Route::get('/ads/create', [MyAdsController::class, 'create'])->name('my-ads.create');
    Route::post('/ads', [MyAdsController::class, 'store'])->name('my-ads.store');
    Route::get('/ads/{ad}', [MyAdsController::class, 'show'])->name('my-ads.show');
    Route::patch('/ads/{ad}', [MyAdsController::class, 'update'])->name('my-ads.update');
    Route::delete('/ads/{ad}', [MyAdsController::class, 'destroy'])->name('my-ads.destroy');
    Route::post('/ad/{ad}/impression', [AdClickController::class, 'impression'])->name('ad.impression');
    Route::get('/ad/{ad}/click', [AdClickController::class, 'click'])->name('ad.click');

    Route::post('/push-subscriptions', [PushSubscriptionController::class, 'store'])->name('push.subscribe');
    Route::delete('/push-subscriptions', [PushSubscriptionController::class, 'destroy'])->name('push.unsubscribe');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/data', [ProfileController::class, 'updateProfileData'])->name('profile.data');
    Route::patch('/profile/theme', [ProfileController::class, 'updateTheme'])->name('profile.theme');
    Route::post('/cookie-consent', [ProfileController::class, 'updateCookieConsent'])->name('cookie.consent');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::patch('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::patch('/users/{user}/toggle-ban', [AdminController::class, 'toggleBan'])->name('users.toggle-ban');
    Route::patch('/users/{user}/toggle-premium', [AdminController::class, 'togglePremium'])->name('users.toggle-premium');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::get('/races', [AdminController::class, 'races'])->name('races');
    Route::delete('/races/{race}', [AdminController::class, 'destroyRace'])->name('races.destroy');

    Route::get('/ads', [AdminAdController::class, 'index'])->name('ads');
    Route::get('/ads/{ad}', [AdminAdController::class, 'show'])->name('ads.show');
    Route::patch('/ads/{ad}/approve', [AdminAdController::class, 'approve'])->name('ads.approve');
    Route::patch('/ads/{ad}/reject', [AdminAdController::class, 'reject'])->name('ads.reject');
    Route::patch('/ads/{ad}/pause', [AdminAdController::class, 'pause'])->name('ads.pause');
    Route::delete('/ads/{ad}', [AdminAdController::class, 'destroy'])->name('ads.destroy');

    Route::get('/pods', [AdminPodController::class, 'index'])->name('pods.index');
    Route::get('/pods/{pod}', [AdminPodController::class, 'show'])->name('pods.show');
    Route::get('/pods/{pod}/edit', [AdminPodController::class, 'edit'])->name('pods.edit');
    Route::patch('/pods/{pod}', [AdminPodController::class, 'update'])->name('pods.update');
    Route::delete('/pods/{pod}', [AdminPodController::class, 'destroy'])->name('pods.destroy');
    Route::delete('/pods/{pod}/members/{userId}', [AdminPodController::class, 'removeMember'])->name('pods.members.remove');

    Route::get('/events', [AdminRaceEventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [AdminRaceEventController::class, 'create'])->name('events.create');
    Route::post('/events', [AdminRaceEventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [AdminRaceEventController::class, 'edit'])->name('events.edit');
    Route::patch('/events/{event}', [AdminRaceEventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [AdminRaceEventController::class, 'destroy'])->name('events.destroy');

    Route::get('/nav-items', [NavItemController::class, 'index'])->name('nav-items');
    Route::post('/nav-items', [NavItemController::class, 'store'])->name('nav-items.store');
    Route::patch('/nav-items/{navItem}', [NavItemController::class, 'update'])->name('nav-items.update');
    Route::delete('/nav-items/{navItem}', [NavItemController::class, 'destroy'])->name('nav-items.destroy');
    Route::patch('/nav-items/{navItem}/toggle', [NavItemController::class, 'toggle'])->name('nav-items.toggle');
    Route::patch('/nav-items/{navItem}/premium', [NavItemController::class, 'togglePremium'])->name('nav-items.premium');
    Route::patch('/nav-items/{navItem}/move', [NavItemController::class, 'move'])->name('nav-items.move');
    Route::patch('/nav-items/{navItem}/location', [NavItemController::class, 'updateLocation'])->name('nav-items.location');
});

require __DIR__.'/auth.php';
