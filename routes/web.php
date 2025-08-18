<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Routing\Route as RoutingRoute;

Route::get('/', [HomeController::class, 'index']);
Route::get('/covoiturage', [HomeController::class, 'index']);
Route::get('/contact', [HomeController::class, 'index']);
Route::get('/login', [HomeController::class, 'index']);
Route::get('/commencer', [HomeController::class, 'index']);
Route::get('/chercher-routes', [HomeController::class, 'index']);
Route::get('mention-legale', [HomeController::class, 'index']);