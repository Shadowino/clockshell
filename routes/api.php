<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\shedule;
use App\Http\Controllers\sheduleController;



Route::get('/today', [sheduleController::class, 'today']); //  получить текущее расписание (нужно для esp8266)
Route::get('/day/{id}', [sheduleController::class, 'SheduleByID']); // получить расписание по id (не ичпользуеться)
Route::get('/date/{date}', [sheduleController::class, 'SheduleByDate']);  // получить расписание по дате (используеться на страницее редактирования)
Route::get('/date/{date1}/{date2}', [sheduleController::class, 'SheduleFromTo']); // получить массив рассписаний с date1 по date2  (используеться на странице list)

Route::post('edit/day', [sheduleController::class, 'editShedule']); // внесение изменений в расписание  (обработчик страницы редактирования)

// все пути начинающиеся с deb будут удалены в будующем
// они нужны для отладки, тестов и экспериментов :)
Route::get('deb/{date?}', [sheduleController::class, 'getTimetable']);

// Route::get('deb/def/{sh?}', [sheduleController::class, 'getDefoultShedule']);

// Route::post('edit/day', function ( Request $req )
// {
//   dd($req->all());
// });



// Route::post('edit/date/{date}', [sheduleController::class, 'editSheduleByDate']);
// Route::get('/day', function(){
//   return"request->day()";
// });
//
