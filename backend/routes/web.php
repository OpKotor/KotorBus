<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SupportController;
use Illuminate\Support\Facades\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Sve rute za web aplikaciju.
| - Korisnici mogu koristiti aplikaciju i izvršiti plaćanje bez logovanja.
| - Nakon uspješnog plaćanja, automatski dobijaju potvrdu i račun na email.
| - Admin panel je zaštićen 'auth:admin' i 'admin' middleware-ima.
| - Pravi admin je bilo koji admin osim korisnika sa name='control'.
| - Readonly admin je korisnik sa name='control' (može samo gledati izvještaje).
|
*/

// Početna stranica - dostupna svima
Route::get('/{any?}', function () {
    $path = base_path('build/index.html');
    if (!file_exists($path)) {
        abort(404, 'index.html not found');
    }
    return response()->file($path);
})->where('any', '.*');

// ======= JAVNE KORISNIČKE RUTE =======

// Prikaz forme za unos podataka i plaćanje
Route::get('/placanje', function () {
    return view('payment');
})->name('placanje.forma');

// Obrada online plaćanja (rezervacija i plaćanje)
Route::post('/procesiraj-placanje', [ReservationController::class, 'processPayment'])
    ->name('process.payment');

// Kontakt/podrška - korisnici šalju upite na bus@kotor.me
Route::get('/podrska', [SupportController::class, 'showForm'])->name('support.form');
Route::post('/podrska', [SupportController::class, 'send'])->name('support.send');

// ================== ADMIN PANEL ==================
Route::prefix('admin')->name('admin.')->group(function () {

    // Login forma za admina (nije zaštićeno)
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');

    // Obrada login forme, zaštita od brute-force napada
    Route::post('login', [LoginController::class, 'login'])
        ->name('login.submit')
        ->middleware('throttle:5,1');

    // Sve ispod ovoga dostupno je SAMO ulogovanom adminu ili readonly adminu (name='control')
    Route::middleware(['auth:admin'])->group(function () {

        // Logout ruta za admina
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');

        // Prikaz izvještaja i pregleda - dostupno i adminu i readonly adminu (name='control')
        Route::get('izvjestaj', [ReportController::class, 'generate'])->name('report');

        // Sve CRUD (dodavanje, brisanje, uređivanje) rute dostupne SAMO pravom adminu (bilo koji admin osim name='control')
        Route::middleware('admin')->group(function () {
            // Dashboard - samo za pravog admina (NE za readonly admina/control)
            Route::get('dashboard', function () {
                return view('admin.dashboard');
            })->name('dashboard');

            // Brisanje rezervacije (primjer admin-only akcije)
            Route::post('brisanje', [ReservationController::class, 'delete'])->name('brisanje');

            // Dodaj ovdje ostale admin-only rute (npr. uređivanje slotova, korisnika, itd.)
        });

        // ========== TEST/DEV RUTE (ukloni ili zaštiti u produkciji) ==========
        if (app()->environment('local')) {
            Route::get('/test-session', function () {
                session(['key' => 'value']);
                return session('key');
            })->middleware('admin');

            Route::get('/test-encrypt-cookie', function () {
                cookie()->queue(cookie('test_cookie', 'test_value', 10));
                return response()->json(['message' => 'Cookie set!']);
            })->middleware('admin');

            Route::get('/test-decrypt-cookie', function () {
                $cookieValue = request()->cookie('test_cookie');
                return response()->json(['cookie_value' => $cookieValue]);
            })->middleware('admin');

            Route::middleware(['web', 'admin'])->post('/ruta-bez-tokena', function () {
                return response()->json(['message' => 'Zahtjev bez CSRF tokena']);
            });

            Route::post('/ruta-sa-tokenom', function () {
                return response()->json(['message' => 'Zahtjev sa CSRF tokenom'], 200);
            })->middleware('admin');
        }
        // ========== KRAJ TEST/DEV RUTA ==========

    });
    Route::get('/{any?}', function () {
        return view('app');
    })->where('any', '.*');

    // Ovo stavi NA KRAJ web.php
    Route::get('/{any?}', function () {
        return response()->file(public_path('build/index.html'));
    })->where('any', '.*');
});

/*
|--------------------------------------------------------------------------
| Napomena za produkciju
|--------------------------------------------------------------------------
| Sve test/development rute (test-session, test-cookie, csrf...) OBAVEZNO ukloni
| ili dodatno zaštiti prije nego što aplikaciju postaviš u produkciju!
| Preporuka: koristi uslov app()->environment('local') da se ove rute izvršavaju
| samo u razvojnom okruženju.
*/