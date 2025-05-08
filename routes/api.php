<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::post('/login-mahasiswa', [AuthController::class, 'login'])->name('api.login');
});

Route::middleware(['auth:sanctum'])->group(function () { });
