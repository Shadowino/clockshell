<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\sheduleController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/edit/{date}', [sheduleController::class, 'editPage']);
Route::get('/list/{date?}', [sheduleController::class, 'listPage']);
Route::get('/list/{date1}/{date2}', [sheduleController::class, 'listPage2']);

// Route::post('list', [sheduleController::class, 'listPage']);
