<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\calendar;

class sheduleController extends Controller
{

  // функция возвращает расписание на день, используеться другими функциями!!
  public function getTimetable($date = null){
    if ($date == null) { $date = date('Y.m.d');} // проверка наличия даты и замена null на текущуюю дату
    $day  = calendar::where('day', $date)->value('timetable'); // возврат даты в виде json ? collection ?
    if ($day == null) {
      return $this->getDefoultShedule();
    } else {
      return $day;
    }
  }


  // может это не лучшее решение ? может стоило сделать:
  // private $var
  public function getDefoultShedule($getshort = false)
  {
    $full = '{"1":["8.30","9.15","9.20","10.05"],"2":["10.15","11.00","11.05","11.50"],"3":["12.00","12.45","12.50","13.35"],"4":["13.45","14.30","14.35","15.20"]}';
    $short  = '{"1":["08:30","09:15"],"2":["09:25","10:10"],"3":["10:20","11:05"],"4":["11:15","12:00"]}';
    if ($getshort) {
      return $short;
    }else {
      return $full;
    }
    return "text";
  }


  public function today(){
    return calendar::where('day', date('Y.m.d'))->value('timetable');
  }

  // Comment
  public function SheduleByID($id){
    return DB::table('calendar')->where('id', $id)->value('timetable');
  }

  // Comment
  public function SheduleByDate($date){
    return DB::table('calendar')->where('day', $date)->value('timetable');
  }

  // Comment ->orderBy('day','asc')
  public function SheduleFromTo($date1, $date2){
    if (date_create_from_format('Y.m.d', $date1) > date_create_from_format('Y.m.d', $date2)){
      $tmp = $date1;
      $date1 = $date2;
      $date2 = $tmp;
    }
    return DB::table('calendar')->where('day', ">=" , $date1)->where('day', '<=', $date2)->pluck('timetable');
  }


  // Comment
  public function editShedule(Request $req){
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
      // echo "update";
      calendar::where('day',  $req->input('date'))->update(['timetable' => json_encode($sh)]);
    }else {
      // создание записи
      // echo "create";
      calendar::insert(['day'  => $req->input('date'), 'timetable' => json_encode($sh)]);
    }
    // echo "<a href='/list'>list</a>";

    return $this->listPage($req);
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
  public function listPage(request $req, $date1 = null){
    if ($date1 == null){
      if ($req->input('date')) {
        $date1 =  $req->input('date');
      }else {
        $date1 = date('Y.m.d');
      }
    }
    // $shedule = ['times' => "8:30 - 9:15", 'date' => "17.06.2022"];
    $tmp = date_parse_from_format("Y.m.d",$date1);
    $date2 = mktime(0, 0, 0, $tmp['month'], $tmp['day']+7, $tmp['year']);
    $shedule = $this->SheduleFromTo($date1,date('Y.m.d',$date2));
    $dates = DB::table('calendar')->where('day', ">=" , $date1)->where('day', '<=', date('Y.m.d',$date2))->pluck('day');
    return view('listShedule',['data' => $shedule, 'dates' => $dates, 'selectdate' => $date1]);
  }


  public function listPage2($date1, $date2){
    if (date_create_from_format('Y.m.d', $date1) > date_create_from_format('Y.m.d', $date2)){
      $tmp = $date1;
      $date1 = $date2;
      $date2 = $tmp;
    }
    $shedule = $this->SheduleFromTo($date1,$date2);
    $dates = DB::table('calendar')->where('day', ">=" , $date1)->where('day', '<=', $date2)->pluck('day');
    return view('listShedule',['data' => $shedule, 'dates' => $dates, 'selectDate' => $date1]);
  }
}
