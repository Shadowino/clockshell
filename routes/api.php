<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\shedule;
use App\Http\Controllers\sheduleController;

//  шаблон
// Route::get('/url', [sheduleController::class, 'method'])

Route::get('/today', [sheduleController::class, 'today']);
Route::get('/day/{id}', [sheduleController::class, 'SheduleByID']);
Route::get('/date/{date}', [sheduleController::class, 'SheduleByDate']);
Route::get('/date/{date1}/{date2}', [sheduleController::class, 'SheduleFromTo']);

Route::post('edit/day', [sheduleController::class, 'editShedule']);

// Route::post('edit/day', function ( Request $req )
// {
//   dd($req->all());
// });



// Route::post('edit/date/{date}', [sheduleController::class, 'editSheduleByDate']);
// Route::get('/day', function(){
//   return"request->day()";
// });
//
// Route::get('/control', [shedule::class, 'index'] );


// [*имяКласса::class, '*имяМетода']

// Route::get('/control', "shedule@index"  ); -- не работает
