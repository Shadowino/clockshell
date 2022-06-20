<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>Расписание дня</title>
  <style media="screen">
  .formBox{
    border: 1px;
    border-radius: 5px;
    background-color: #DDD;
    width: 400px;
    margin-left: auto;
    margin-right: auto;
    padding: 15px;
  }
  h4{
    margin-bottom: 1px;
  }
  h3{
    margin-top: 15px;
  }
  .p{
    margin-top: 15px;
    margin-bottom: 15px;
  }
  input{
    margin-left: 3px;
  }
  </style>
</head>
<body>
  <div class="formBox">
    <form class="" action="/api/edit/day" method="post">
      @csrf
      <h3>редактор расписания</h3>
      <!-- <input type="date" name="answer" value="<?php echo date('Y-m-d'); ?>"> -->
      <h4>Дата</h4>
      <input type="date" name="date" value="{{$date}}" required>
      <h4>Применить шаблон:</h4>
      <input type="text" name="b" value="" list="blueParse">

      <datalist id="blueParse">
        <option value="standart">стандартное</option>
        <option value="short">сокращенное</option>
      </datalist>

      <?php

      for ($d=1; $d < 5; $d++) {
        echo "<div class='p' ><h4>пара №$d</h4>";
        for ($c=0; $c < 4; $c++) {
          echo "<input type='time' name='$d$c' value='23:59' required>";
        }
        echo "</div>";
      }

      ?>

      <!-- <div class="p">
        <h4>Первая пара</h4>
        <input type="time" name="A1" value="08:30" required>
        <input type="time" name="A2" value="09:15" required>
        <input type="time" name="A3" value="09:20" required>
        <input type="time" name="A4" value="10:05" required>
      </div> -->

      <p>
        <input type="submit">
      </p>
    </form>
  </div>
</body>
</html>
