<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

// ======= JAVNE KORISNIČKE RUTE =======

// Forma za plaćanje (prikazuje formu)
Route::get('/placanje', [PaymentController::class, 'showForm'])->name('payment.form');

// Procesiranje plaćanja (submit forme)
//Route::post('/procesiraj-placanje', function (\Illuminate\Http\Request $request) {
 //   \Log::info('POST /procesiraj-placanje', $request->all());
 //   return response()->json(['message' => 'OK', 'data' => $request->all()]);
//});
Route::post('/procesiraj-placanje', function(Request $req) {
    Log::info('Stigao je POST na /procesiraj-placanje', $req->all());
    return response()->json(['msg' => 'OK', 'data' => $req->all()]);
});

// Callback za online plaćanje
Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');

// Podrška
Route::get('/podrska', [SupportController::class, 'showForm'])->name('support.form');
Route::post('/podrska', [SupportController::class, 'send'])->name('support.send');

// ================== ADMIN PANEL ==================
Route::prefix('admin')->name('admin.')->group(function () {
    // Login forma za admina (nije zaštićeno)
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])
        ->name('login.submit')
        ->middleware('throttle:5,1');

    // Sve ispod ovoga dostupno je SAMO ulogovanom adminu ili readonly adminu (name='control')
    Route::middleware(['auth:admin'])->group(function () {
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('izvjestaj', [ReportController::class, 'generate'])->name('report');
        Route::middleware('admin')->group(function () {
            Route::get('dashboard', function () {
                return view('admin.dashboard');
            })->name('dashboard');
            Route::post('brisanje', [ReservationController::class, 'delete'])->name('brisanje');
        });
    });

    // ========== TEST/DEV RUTE ==========
    if (app()->environment('local')) {
        Route::get('test-dnevni-finansijski', [ReportController::class, 'sendDailyFinance']);
        Route::get('test-dnevni-vozila', [ReportController::class, 'sendDailyVehicleReservations']);
        Route::get('test-mjesecni-finansijski', [ReportController::class, 'sendMonthlyFinance']);
        Route::get('test-mjesecni-vozila', [ReportController::class, 'sendMonthlyVehicleReservations']);
        Route::get('test-godisnji-finansijski', [ReportController::class, 'sendYearlyFinance']);
        Route::get('test-godisnji-vozila', [ReportController::class, 'sendYearlyVehicleReservations']);
        // Test payment ruta (samo za dev)
        Route::get('test-payment', [PaymentController::class, 'test']);
    }

    // OVA RUTA MORA BITI NA SAMOM KRAJU ADMIN GRUPE!
    Route::get('/{any}', function () {
        return response()->file(public_path('build/index.html'));
    })->where('any', '.*');
});

Route::get('/test-csrf', function () {
    return view('test-csrf');
});

// Dodaj ovo PRE nego što dođeš do GET catch-all!
Route::post('/{any}', function () {
    abort(404, 'POST ruta ne postoji');
})->where('any', '.*');

// === GLOBAL CATCH-ALL RUTA NA SAMOM KRAJU web.php ===
Route::get('/{any}', function () {
    $path = base_path('build/index.html');
    if (!file_exists($path)) {
        abort(404, 'index.html not found');
    }
    return response()->file($path);
})->where('any', '.*');