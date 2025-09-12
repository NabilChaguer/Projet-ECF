<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CovoiturageController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/covoiturage', [CovoiturageController::class, 'index'])->name('covoiturages.index');
Route::post('/covoiturage/search', [CovoiturageController::class, 'search'])->name('covoiturages.search');

Route::get('/contact', [HomeController::class, 'index']);
Route::get('/login', [HomeController::class, 'index']);
Route::get('/commencer', [HomeController::class, 'index']);
Route::get('/chercher-routes', [HomeController::class, 'index']);
Route::get('mention-legale', [HomeController::class, 'index']);