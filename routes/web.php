<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CovoiturageController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AuthController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/covoiturage', [CovoiturageController::class, 'index'])->name('covoiturages.index');
Route::post('/covoiturage/search', [CovoiturageController::class, 'search'])->name('covoiturages.search');

Route::post('/covoiturage/{id}/reserver', [ReservationController::class, 'store'])->name('covoiturage.reserver');

Route::get('/login', [AuthController::class, 'afficherFormulaireLogin'])->name('login.formulaire');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/register', [AuthController::class, 'afficherFormulaireRegister'])->name('register.formulaire');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/contact', [HomeController::class, 'index']);
Route::get('/commencer', [HomeController::class, 'index']);
Route::get('/chercher-routes', [HomeController::class, 'index']);
Route::get('mention-legale', [HomeController::class, 'index']);