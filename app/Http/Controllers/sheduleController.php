<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\calendar;

class sheduleController extends Controller
{
  // Comment
  public function SheduleByID($id){
    return DB::table('calendar')->where('id', $id)->value('timetable');
  }

  // Comment
  public function SheduleByDate($date){
    return DB::table('calendar')->where('day', $date)->value('timetable');
  }

  // Comment
  public function SheduleFromTo($date1, $date2){
    return DB::table('calendar')->where('day', ">=" , $date1)->where('day', '<=', $date2)->pluck('timetable');
  }


  // Comment
  public function editShedule(Request $req){

    // dd($req->all());

    $sh = [1=>["8:30"]];
    for ($d=1; $d < 5; $d++) {
      for ($c=0; $c < 4; $c++) {
        $sh[$d][$c] = $req->input("$d$c");
      }
    }


    $day =  calendar::where('day', $req->input('date'))->pluck('timetable')->all();
    // print_r($day);
    if($day != null){
      // обновление  записи
      echo "update";
      calendar::where('day',  $req->input('date'))->update(['timetable' => json_encode($sh)]);
    }else {
      // создание записи
      echo "create";
      calendar::insert(['day'  => $req->input('date'), 'timetable' => json_encode($sh)]);
    }
      echo "<a href='/list'>list</a>";
    // calendar::where('day', $req->input('date'))->exists()
    // echo calendar::where('day', $req->input('date'))->pluck('timetable')->all()[0];
    // echo "<br><br>";
    // echo json_encode($sh);
    // return $sh;

  }

  // Comment
  public function editSheduleByDate($date){
    return;
  }


  // Comment
  public function editPage($date){
    return view('editForm', ['date' => $date]);
  }

  // Comment
  public function listPage($date1 = null){
    if ($date1 == null){
      $date1 = date('Y.m.d');
    }
    // $shedule = ['times' => "8:30 - 9:15", 'date' => "17.06.2022"];
    $tmp = date_parse_from_format("Y.m.d",$date1);
    $date2 = mktime(0, 0, 0, $tmp['month'], $tmp['day']+7, $tmp['year']);
    $shedule = $this->SheduleFromTo($date1,date('Y.m.d',$date2));
    $dates = DB::table('calendar')->where('day', ">=" , $date1)->where('day', '<=', date('Y.m.d',$date2))->pluck('day');
    return view('listShedule',['data' => $shedule, 'dates' => $dates]);
  }


  public function listPage2($date1, $date2){
    // $shedule = ['times' => "8:30 - 9:15", 'date' => "17.06.2022"];
    $shedule = $this->SheduleFromTo($date1,$date2);
    $dates = DB::table('calendar')->where('day', ">=" , $date1)->where('day', '<=', $date2)->pluck('day');
    return view('listShedule',['data' => $shedule, 'dates' => $dates]);
  }
}
