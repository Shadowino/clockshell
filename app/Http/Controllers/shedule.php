<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use App\Models\calendar;


class shedule extends Controller
{
    public function index() {
      $control = DB::table('calendar')->get();

      return $control[0]->timetable;
      // foreach ($control as $con) {
      //   echo $con->timetable;
      // }
    }
}
      // $call = DB::select('select * from calendar limit 1');
      // $call = json_decode(json_encode($call[0]), true)['timetable'];
      // return json_decode($call, true)['one'];//

      /*
      кароч 2:36
      DB:select(); -- возвращает МАССИВ из обьектов "stdClass"
      json_encode() --конвертирует stdClass в JSON обьект
      json_decode(json, true) -- конвертирует json в массив
      это нужно для конвертации stdClass в МАССИВ array чтобы
      достать из него нужные (или нет?) данные....

      */


      // return response()->json($call[0]);
    // public function index() {  limit 1
    //   $call = DB::select('select `timetable` from calendar');
    //   return response()->json($call);
    // }
