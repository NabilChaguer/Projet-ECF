<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;


Route::get('/', [HomeController::class, 'index']);
Route::get('/carpooling', [HomeController::class, 'index']);
Route::get('/contact', [HomeController::class, 'index']);
Route::get('/learn-more', [HomeController::class, 'index']);
Route::get('/legal-notices', [HomeController::class, 'index']);
Route::get('/login', [HomeController::class, 'index']);
Route::get('/register', [HomeController::class, 'index']);
Route::get('/search-routes', [HomeController::class, 'index']);