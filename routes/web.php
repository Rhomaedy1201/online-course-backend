<?php

use App\Http\Controllers\ExportCourse;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/download-silabus', ExportCourse::class);
