<?php
namespace App\Calendars\Admin;
use Carbon\Carbon;
use App\Models\Calendars\ReserveSettings;

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
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table m-auto border adjust-table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th class="border">月</th>';
    $html[] = '<th class="border">火</th>';
    $html[] = '<th class="border">水</th>';
    $html[] = '<th class="border">木</th>';
    $html[] = '<th class="border">金</th>';
    $html[] = '<th class="border">土</th>';
    $html[] = '<th class="border">日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';

    $weeks = $this->getWeeks();

    foreach ($weeks as $week) {
        $html[] = '<tr class="' . $week->getClassName() . '">';
        $days = $week->getDays();
        foreach ($days as $day) {
          $startDay = $this->carbon->copy()->startOfMonth();
          $toDay = Carbon::today(); // 現在の日付と比較

            // 過ぎた日かどうかのチェック
            if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){
              $html[] = '<td class="past-day border">';
              $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px">受付終了</p>';
            } else {
              $html[] = '<td class="border '.$day->getClassName().'">';
            }
            $html[] = $day->render();
            $html[] = '<div class="adjust-area">';

            // 過ぎた日の場合、入力欄をdisabledに
            if ($dayDate->lt($toDay)) {
                $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" name="reserve_day[' . $day->everyDay() . '][1]" type="text" form="reserveSetting" value="' . $day->onePartFrame($day->everyDay()) . '" disabled></p>';
                $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" name="reserve_day[' . $day->everyDay() . '][2]" type="text" form="reserveSetting" value="' . $day->twoPartFrame($day->everyDay()) . '" disabled></p>';
                $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" name="reserve_day[' . $day->everyDay() . '][3]" type="text" form="reserveSetting" value="' . $day->threePartFrame($day->everyDay()) . '" disabled></p>';
            } else {
                $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" name="reserve_day[' . $day->everyDay() . '][1]" type="text" form="reserveSetting" value="' . $day->onePartFrame($day->everyDay()) . '"></p>';
                $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" name="reserve_day[' . $day->everyDay() . '][2]" type="text" form="reserveSetting" value="' . $day->twoPartFrame($day->everyDay()) . '"></p>';
                $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" name="reserve_day[' . $day->everyDay() . '][3]" type="text" form="reserveSetting" value="' . $day->threePartFrame($day->everyDay()) . '"></p>';
            }

            $html[] = '</div>';
            $html[] = '</td>'; // tdを閉じる
        }
        $html[] = '</tr>'; // trを閉じる
    }

    $html[] = '</tbody>'; // tbodyを閉じる
    $html[] = '</table>'; // tableを閉じる
    $html[] = '</div>'; // divを閉じる
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
