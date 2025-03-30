<?php
namespace App\Calendars\Admin;
//adminファイルは管理者のファイル
use Carbon\Carbon;
use App\Models\Calendars\ReserveSettings;
//このファイルには、カレンダーの設定に関連するクラスやメソッドが含まれている。例えば、予約設定やカレンダーの基本的な構成を管理する機能　全体の設定を集中管理する役割がある
class CalendarSettingView{
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

    foreach ($weeks as $week) {
        $html[] = '<tr class="' . $week->getClassName() . '">';
        $days = $week->getDays();
        foreach ($days as $day) {
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
            $html[] = '<div class="adjust-area">';
              if($day->everyDay()){
              if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){
                $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" name="reserve_day[' . $day->everyDay() . '][1]" type="text" form="reserveSetting" value="' . $day->onePartFrame($day->everyDay()) . '" disabled></p>';
                $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" name="reserve_day[' . $day->everyDay() . '][2]" type="text" form="reserveSetting" value="' . $day->twoPartFrame($day->everyDay()) . '" disabled></p>';
                $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" name="reserve_day[' . $day->everyDay() . '][3]" type="text" form="reserveSetting" value="' . $day->threePartFrame($day->everyDay()) . '" disabled></p>';
            } else {
                $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" name="reserve_day[' . $day->everyDay() . '][1]" type="text" form="reserveSetting" value="' . $day->onePartFrame($day->everyDay()) . '"></p>';
                $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" name="reserve_day[' . $day->everyDay() . '][2]" type="text" form="reserveSetting" value="' . $day->twoPartFrame($day->everyDay()) . '"></p>';
                $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" name="reserve_day[' . $day->everyDay() . '][3]" type="text" form="reserveSetting" value="' . $day->threePartFrame($day->everyDay()) . '"></p>';
            }
          }
            $html[] = '</div>';
            $html[] = '</td>';
        }
        $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '</div>';
    $html[] = '<form action="' . route('calendar.admin.update') . '" method="post" id="reserveSetting">' . csrf_field() . '</form>';

    return implode("", $html); // 結果を返す
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
