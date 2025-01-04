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
        $toDay = Carbon::today()->format("Y-m-d"); //今日　Carbonの方が日付の加減算ができてコードの可読性が向上する

        if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){ //月の最初の初めの日が現在よりも早いか && 遅いか
          $html[] = '<td class="past-day border">';
          $html[] = $day->render(); //特定の日付を表し、その日に関する情報を保持
          $html[] = '<p class="m-auto p-0 w-75" style="font-size:14px; color: #222222;">受付終了</p>';
          $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
          $html[] = '</td>';
        }else { //今日以降だった場合
          $html[] = '<td class="calendar-td '.$day->getClassName().'">';
        $html[] = $day->render();
        $html[] = '<div class="adjust-area">';

        if(in_array($day->everyDay(), $day->authReserveDay())){
          $reservePart = $day->authReserveDate($day->everyDay())->first()->setting_part;
          if($reservePart == 1){
            $reservePart = "リモ1部";
          }else if($reservePart == 2){
            $reservePart = "リモ2部";
          }else if($reservePart == 3){
            $reservePart = "リモ3部";
            $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">'.csrf_field().'<button type="submit" class="btn btn-danger p-0 w-75" name="delete_date" style="font-size:12px" form="deleteParts" value="'. $reservePart .'">'. $reservePart .'</button></form>';
            $html[] = '<button type="submit" class="btn btn-danger p-0 w-75" name="delete_date" style="font-size:12px" value="'. $reservePart .'">'. $reservePart .'</button>';
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
          }
        } else {
          $html[] = $day->selectPart($day->everyDay()); } $html[] = '</td>';
        }
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