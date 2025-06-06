<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimeSlotController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\VehicleTypeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SystemConfigController;
use App\Http\Controllers\ExampleController;
use App\Http\Controllers\MailController;

/*
|--------------------------------------------------------------------------
| JAVNE RUTE (korisnici bez logovanja)
|--------------------------------------------------------------------------
*/
Route::get('reservations/slots', [ReservationController::class, 'showSlots']);
Route::post('reservations/reserve', [ReservationController::class, 'reserve'])->middleware('throttle:10,1');
Route::get('timeslots', [TimeSlotController::class, 'index']);
Route::get('timeslots/available', [TimeSlotController::class, 'availableSlots']);
Route::apiResource('vehicle-types', VehicleTypeController::class)->only(['index', 'show']);
Route::get('reservations/by-date', [ReservationController::class, 'byDate']);

/*
|--------------------------------------------------------------------------
| ADMIN RUTE (zaštićene, potrebna autentifikacija)
|--------------------------------------------------------------------------
|
| - Svi admini (pravi + readonly "control") mogu gledati podatke (npr. GET).
| - Samo pravi admin (NIJE name='control') može raditi izmjene (POST/PUT/PATCH/DELETE).
|--------------------------------------------------------------------------
*/

// Sve admin GET rute koje nisu javne, npr. pregled admin korisnika ili drugih podataka
Route::middleware(['auth:sanctum'])->group(function () {
    // Pregled rezervacija (ako NE ŽELIŠ da obični korisnici vide rezervacije svih korisnika)
    Route::get('reservations', [ReservationController::class, 'index']);
    Route::get('reservations/{reservation}', [ReservationController::class, 'show']);

    // Pregled admin korisnika (npr. lista)
    Route::get('admins', [AdminController::class, 'index']);

    // Ostale GET rute po potrebi (koje ne moraju biti javne)...
});

// Rute koje samo pravi admin može koristiti (NE readonly admin tj. name='control')
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // Upravljanje slotovima (sve osim prikaza!)
    Route::apiResource('timeslots', TimeSlotController::class)->except(['index', 'show']);

    // Upravljanje rezervacijama (brisanje)
    Route::delete('reservations/{reservation}', [ReservationController::class, 'destroy']);

    // Upravljanje tipovima vozila (kreiranje, izmjena, brisanje)
    Route::apiResource('vehicle-types', VehicleTypeController::class)->except(['index', 'show']);

    // Upravljanje admin korisnicima
    Route::apiResource('admins', AdminController::class)->except(['index']);

    // Promjena statusa rezervacije
    Route::patch('reservations/{id}/status', [ReservationController::class, 'updateStatus']);

    // Postavljanje sistemske konfiguracije
    Route::post('system-config', [SystemConfigController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| AUTENTIFIKACIJA ADMINA
|--------------------------------------------------------------------------
*/
Route::post('admin/login', [AdminController::class, 'login']);
Route::post('admin/logout', [AdminController::class, 'logout'])->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| PRIMJER RESTful RUTA ZA ExampleController
|--------------------------------------------------------------------------
*/
Route::apiResource('examples', ExampleController::class);

/*
|--------------------------------------------------------------------------
| RUTE ZA SLANJE EMAIL-OVA
|--------------------------------------------------------------------------
*/
Route::post('send-payment-confirmation', [MailController::class, 'sendPaymentConfirmation'])
    ->name('api.mail.payment-confirmation');
Route::post('send-reservation-confirmation', [MailController::class, 'sendReservationConfirmation'])
    ->name('api.mail.reservation-confirmation');

/*
|--------------------------------------------------------------------------
| TEST RUTE
|--------------------------------------------------------------------------
*/
Route::get('test', fn() => response()->json(['ok' => true]));
Route::get('testjson', fn() => response()->json(['ok' => true]));

// Dodaj ovde CORS test rutu:
Route::get('cors-test', fn() => response()->json(['ok' => true]));

/*
|--------------------------------------------------------------------------
| Dostupnost slota
|--------------------------------------------------------------------------
*/
Route::get('slots/{slot_id}/availability', [TimeSlotController::class, 'availability']);

/*
|--------------------------------------------------------------------------
| NAPOMENA:
| - SVE GET rute koje želiš da frontend može koristiti bez logina, drži u javnim rutama.
| - NIKAD ne dupliraj iste GET rute u javnim i zaštićenim grupama.
| - Kreiranje/izmjena/birsanje treba biti samo pod admin middleware-om.
|--------------------------------------------------------------------------
*/