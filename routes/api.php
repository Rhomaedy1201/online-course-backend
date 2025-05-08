<?php

use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware(['guest'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/role', [RoleController::class, 'index']);
    Route::post('/role-store', [RoleController::class, 'store']);
    Route::get('/role-edit/{id}', [RoleController::class, 'edit']);
    Route::put('/role-update/{id}', [RoleController::class, 'update']);
    Route::delete('/role-delete/{id}', [RoleController::class, 'destroy']);

    Route::get('/user', [UserController::class, 'index']);
});
