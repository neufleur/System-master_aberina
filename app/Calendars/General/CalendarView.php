<?php
namespace App\Calendars\General;
//Generalファイルは一般の人も操作できるファイル
use Carbon\Carbon;
use Auth;

//このファイルには、カレンダーの表示や描画に関連するクラスやメソッドが含まれる。カレンダーをHTML形式でレンダリングし、ユーザーインターフェースに表示する機能　ユーザーにカレンダーを視覚的に提供する役割がある
class CalendarView{
  private $carbon;

  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  public function getTitle(){
    return $this->carbon->format('Y年n月');
  }

  function render(){
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th>月</th>';
    $html[] = '<th>火</th>';
    $html[] = '<th>水</th>';
    $html[] = '<th>木</th>';
    $html[] = '<th>金</th>';
    $html[] = '<th>土</th>';
    $html[] = '<th>日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';
    $weeks = $this->getWeeks(); //週ごとのデータを保持 $weeks 配列の各要素（週）に対してループをする
    foreach($weeks as $week){
      $html[] = '<tr class="'.$week->getClassName().'">';

      $days = $week->getDays();  //getDays() メソッドでその週の全ての日を取得
      foreach($days as $day){
        $startDay = $this->carbon->copy()->format("Y-m-01"); //月の最初の初めの日
        $toDay = $this->carbon->copy()->format("Y-m-d");
        $yesterday = $this->carbon->copy()->subDay()->format("Y-m-d");
        //過去の日
         if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){ //月の最初の初めの日が現在よりも早いか && 遅いか　カレンダーの表示
          $html[] = '<td class="past-day border">';
        }else {

        //今日以降だった場合
        $html[] = '<td class="calendar-td '.$day->getClassName().'">';
        }
      $html[] = $day->render();

        // 予約済みの日付かチェック　
        if(in_array($day->everyDay(), $day->authReserveDay())){
          $reservePart = $day->authReserveDate($day->everyDay())->first()->setting_part;
          if($reservePart == 1){
            $reservePart = "リモ1部";
          }else if($reservePart == 2){
            $reservePart = "リモ2部";
          }else if($reservePart == 3){
            $reservePart = "リモ3部";
          }
          //⓵
          if (in_array($day->everyDay(), $day->authReserveDay())) {
            $reservePart = $day->authReserveDate($day->everyDay())->first()->setting_part;
            $reservePartLabel = ['リモ1部', 'リモ2部', 'リモ3部'][$reservePart - 1];
            $html[] = '<p class="m-auto p-0 w-75" style="font-size:14px; color: black;">' . $reservePartLabel . '</p>';
          }else{
            $html[] = '<p class="m-auto p-0 w-75" style="font-size:14px; color: #222222;">受付終了</p>';
          }
          $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
    //     }else {
    //          $html[] = '<td class="calendar-td '.$day->getClassName().'">';
    // $html[] = $day->render();
    //     }
          //⓶
          if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){ //予約済みの日かどうかチェック
            $html[] = '<p class="m-auto p-0 w-75" style="font-size:14px; color: #222222;"></p>';
            // $html[] = '<p>受付終了</p>';

          }else{
            $html[] = '<button type="submit" class="btn btn-danger p-0 w-75" name="delete_date" style="font-size:12px" value="'. $day->authReserveDate($day->everyDay())->first()->setting_reserve .'">'. $reservePart .'</button>';
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';

          }
        }else{
          // if($startDay > $day->everyDay() || $toDay < $day->everyDay()) {//今日以降のみ選択可能（過去の日は選択肢を表示しない）
          $html[] = $day->selectPart($day->everyDay());//このコード行は、カレンダーの各日の予約可能な部数を選択するため
        // }

      }
        $html[] = $day->getDate();
        $html[] = '</td>';
      }
      
      $html[] = '</tr>';
    }

    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">'.csrf_field().'</form>';
    $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">'.csrf_field().'</form>';

    return implode('', $html);
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