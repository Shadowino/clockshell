<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>посмотреть расписания</title>
  <style media="screen">
  .dateLine{
    background-color:rgb(253, 173, 6);
    width: 100%;
    height: 60px;
  }
  .lenta{
    margin-top: 10px;
    margin-bottom: 10px;
    width: 100%;
  }
  .date{
    width: 150px;
    border: 1px;
    display: inline-block;
    text-align: center;
    height: 20px;
    margin-top: 20px;
    margin-bottom: 20px;
  }
  .day{
    display: inline-block;
    width: 150px;
    border: 1px;
    vertical-align: top;

  }
  .double{
    margin-bottom: 15px;
    display: flex;
  }
  .mark{
    display: block;
    background-color: rgba(2, 82, 249);
    width: 30px;

  }
  .times{
    vertical-align: top;
    text-align: right;
    display: inline-block;
    min-width: 90px;
  }
  p {
    margin-top: 0px;
    margin-bottom: 10px;
  }
  a{
    color: black;
  }
  .day:hover{
    background-color: #DDD;
  }
  </style>
</head>
<body>
  <div class="lenta">
    <form class="" action="/list" method="get">
      <div class="day">
         <input type="submit" name="" value="показат с даты">
      </div>
      <div class="day">
        <input type="date" name="date" value="<?php echo $selectdate ?>">
      </div>
    </form>
  </div>
  <div class="dateLine">
    <?php
    $dates = $dates->all();
    foreach ($dates as $date) {
      echo "<a href='edit/$date'><div class='date'>
      <p>$date</p>
      </div></a>";
    }


    ?>
  </div>


  <div class="lenta">

    <?php
    $days = $data->all();
    $h = array("первая","вторая","третья","четвертая");
    foreach ($days as $key => $day) {
      echo "<a href='/edit/$dates[$key]'><div class='day'>";
      $day = json_decode($day, true);
      $cntd = 0;
      foreach ($day as $double) {
        echo "<div class='double'>
        <div class='mark'></div>
        <div class='times'>
        <p> $double[0] - $double[1] </p>";
        if (isset($double[2])) {
          echo "<p>$double[2] - $double[3]</p>";
        }
        echo "
        </div>
        </div>";
        $cntd++;
      }
      echo "</div></a>";
    }
    ?>
  </div>


</body>
</html>





<!--
// -->
