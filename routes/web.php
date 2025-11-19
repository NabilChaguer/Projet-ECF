<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CovoiturageController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MonEspaceController;

// === Pages publiques ===
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact', [HomeController::class, 'index'])->name('contact');
Route::get('/chercher-routes', [HomeController::class, 'index'])->name('chercher.routes');
Route::get('/mention-legale', [HomeController::class, 'index'])->name('mention.legale');

// === Authentification ===
Route::get('/login', [AuthController::class, 'afficherFormulaireLogin'])->name('login.formulaire');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'afficherFormulaireRegister'])->name('register.formulaire');
Route::get('/commencer', [AuthController::class, 'afficherFormulaireRegister'])->name('commencer');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// === Covoiturage & Réservations ===
Route::get('/covoiturage', [CovoiturageController::class, 'index'])->name('covoiturages.index');
Route::post('/covoiturage/search', [CovoiturageController::class, 'search'])->name('covoiturages.search');
Route::post('/covoiturage/{id}/reserver', [ReservationController::class, 'store'])->name('covoiturage.reserver');

// === Espace personnel ===
Route::middleware(['auth'])->group(function () {
    Route::get('/mon-espace', [MonEspaceController::class, 'show'])->name('mon-espace');
    Route::post('/mon-espace/update', [MonEspaceController::class, 'update'])->name('mon-espace.update');
    Route::delete('/voitures/{voiture}', [MonEspaceController::class, 'destroy'])->name('voitures.destroy');

    // Historique / annulation de covoiturages
    Route::get('/mon-espace/historique', [MonEspaceController::class, 'historique'])->name('mon-espace.historique');
    Route::post('/covoiturages/{covoiturage}/annuler',[ReservationController::class, 'annuler'])->name('covoiturage.annuler');
    Route::delete('/mes-reservations/{covoiturage}/annuler', [MonEspaceController::class, 'annuler'])->name('mon-espace.reservation.annuler')->middleware('auth');
    Route::delete('/covoiturage/supprimer-definitif/{id}',[MonEspaceController::class, 'supprimerDefinitif'])->name('covoiturage.supprimer.definitif');

// === Saisie d’un voyage (formulaire + enregistrement) ===
Route::middleware(['auth'])->group(function () {
    Route::get('/mon-espace/voyage/saisir', [MonEspaceController::class, 'saisirVoyage'])->name('voyages.create');
    Route::post('/mon-espace/voyage', [MonEspaceController::class, 'storeVoyage'])->name('voyages.store');
});

});
