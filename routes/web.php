<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\sheduleController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/edit/{date}', [sheduleController::class, 'editPage']);
Route::get('/list/{date?}', [sheduleController::class, 'listPage']);
Route::get('/list/{date1}/{date2}', [sheduleController::class, 'listPage2']);

// Route::post('list', [sheduleController::class, 'listPage']);
