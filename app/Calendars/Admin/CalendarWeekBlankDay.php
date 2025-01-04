<?php
namespace App\Calendars\Admin;

class CalendarWeekBlankDay extends CalendarWeekDay{

  function getClassName(){
    return "day-blank";
  }

  function render(){
    return '';
  }

  function everyDay(){
    return '';
  }

  function dayPartCounts($ymd = null){
    return '';
  }

  function dayNumberAdjustment(){
    return '';
  }
  public function selectPart($day) {
    // 実際の処理をここに書く
    // 例えば、$day に基づいて適切な「部」を選択するなど
    // ここでは簡単な例を示します
    return '';
}
}