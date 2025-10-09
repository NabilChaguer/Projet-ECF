<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CovoiturageController;
use App\Http\Controllers\ReservationController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/covoiturage', [CovoiturageController::class, 'index'])->name('covoiturages.index');
Route::post('/covoiturage/search', [CovoiturageController::class, 'search'])->name('covoiturages.search');

Route::post('/covoiturage/{id}/reserver', [ReservationController::class, 'store'])->name('covoiturage.reserver');

Route::get('/login', function () {return view('login');})->name('login');


Route::get('/contact', [HomeController::class, 'index']);
Route::get('/commencer', [HomeController::class, 'index']);
Route::get('/chercher-routes', [HomeController::class, 'index']);
Route::get('mention-legale', [HomeController::class, 'index']);