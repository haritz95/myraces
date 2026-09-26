<?php

use App\Http\Controllers\OfflineController;
use Illuminate\Support\Facades\Route;

// La web es solo la portada de la app MyRaces para iPhone: sin cuentas ni inicio de sesión.
// La web antigua está en routes/legacy.php, sin cargar.

Route::get('/sitemap.xml', fn () => response()->view('sitemap')->header('Content-Type', 'application/xml'))->name('sitemap');

Route::get('/', fn () => view('welcome'))->name('home');

Route::get('/offline', OfflineController::class)->name('offline');

Route::view('/privacidad', 'legal.privacy')->name('privacy');
Route::redirect('/privacy', '/privacidad');

Route::get('/language/{locale}', function (string $locale) {
    if (in_array($locale, ['es', 'en'])) {
        session(['locale' => $locale]);
    }

    return redirect()->back();
})->name('language.switch');

// Cualquier otra dirección (las de la web antigua: /login, /dashboard…) lleva a la portada.
Route::fallback(fn () => redirect()->route('home'));
