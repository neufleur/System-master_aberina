<?php
namespace App\Calendars\Admin;
use Carbon\Carbon;
use App\Models\Users\User;

class CalendarView{
  private $carbon;


  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  public function getTitle(){
    return $this->carbon->format('Y年n月');
  }

  public function render(){
    $html = [];
    $html[] = '<div class="calendar text-center" style="max-width: 100%;">';
    $html[] = '<div class="calendar-wrapper" style="overflow-x: auto;">';
    $html[] = '<table class="table m-auto border adjust-table" style="min-width: 100%;">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th class="border">月</th>';
    $html[] = '<th class="border">火</th>';
    $html[] = '<th class="border">水</th>';
    $html[] = '<th class="border">木</th>';
    $html[] = '<th class="border">金</th>';
    $html[] ='<th class="saturday">土</th>';
    $html[] ='<th class="sunday">日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';

    $weeks = $this->getWeeks();

    foreach($weeks as $week){
      $html[] = '<tr class="'.$week->getClassName().'">';
      $days = $week->getDays();
      foreach($days as $day){
        $startDay = $this->carbon->format("Y-m-01");
        $toDay = $this->carbon->format("Y-m-d");
        $carbonDay = Carbon::parse($day->everyDay());
        $dayOfWeek = $carbonDay->dayOfWeek; // 0(日曜) ～ 6(土曜)

        $class = '';
        if ($dayOfWeek === 6) {
            $class = 'saturday'; // 土曜のクラス
        } elseif ($dayOfWeek === 0) {
            $class = 'sunday';   // 日曜のクラス
        }

        if($startDay <= $day->everyDay() && $toDay > $day->everyDay()){
          $html[] = '<td class="past-day border '.$class.'">';
        }else{
          $html[] = '<td class="border '.$day->getClassName().''.$class.'">';
        }

        $html[] = $day->render();
        $html[] = $day->dayPartCounts($day->everyDay());
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '</div>';


    return implode("", $html);
  }

  protected function getWeeks(){
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth();
    $lastDay = $this->carbon->copy()->lastOfMonth();
    $week = new CalendarWeek($firstDay->copy());
    $weeks[] = $week;
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    while($tmpDay->lte($lastDay)){
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}