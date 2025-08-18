<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/commencer', [HomeController::class, 'index']);
Route::get('/chercher-routes', [HomeController::class, 'index']);
Route::get('mention-legale', [HomeController::class, 'index']);