<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\NavItemController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GearController;
use App\Http\Controllers\PersonalRecordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RaceCoachController;
use App\Http\Controllers\RaceController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\StatsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/language/{locale}', function (string $locale) {
    if (in_array($locale, ['es', 'en'])) {
        session(['locale' => $locale]);
    }

    return redirect()->back();
})->name('language.switch');

Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('races', RaceController::class);

    Route::get('/stats', [StatsController::class, 'index'])->name('stats.index');

    Route::resource('expenses', ExpenseController::class)->except(['show']);

    Route::get('/personal-records', [PersonalRecordController::class, 'index'])->name('personal-records.index');
    Route::post('/personal-records', [PersonalRecordController::class, 'store'])->name('personal-records.store');
    Route::delete('/personal-records/{personalRecord}', [PersonalRecordController::class, 'destroy'])->name('personal-records.destroy');

    Route::resource('gear', GearController::class)->except(['show']);

    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');

    Route::get('/coach', [RaceCoachController::class, 'index'])->name('coach.index');
    Route::post('/coach/chat', [RaceCoachController::class, 'chat'])->name('coach.chat');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/theme', [ProfileController::class, 'updateTheme'])->name('profile.theme');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::patch('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::patch('/users/{user}/toggle-ban', [AdminController::class, 'toggleBan'])->name('users.toggle-ban');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::get('/races', [AdminController::class, 'races'])->name('races');
    Route::delete('/races/{race}', [AdminController::class, 'destroyRace'])->name('races.destroy');

    Route::get('/nav-items', [NavItemController::class, 'index'])->name('nav-items');
    Route::patch('/nav-items/{navItem}/toggle', [NavItemController::class, 'toggle'])->name('nav-items.toggle');
    Route::patch('/nav-items/{navItem}/premium', [NavItemController::class, 'togglePremium'])->name('nav-items.premium');
    Route::patch('/nav-items/{navItem}/move', [NavItemController::class, 'move'])->name('nav-items.move');
    Route::patch('/nav-items/{navItem}/location', [NavItemController::class, 'updateLocation'])->name('nav-items.location');
});

require __DIR__.'/auth.php';
