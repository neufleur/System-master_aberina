<?php
namespace App\Searchs;

use App\Models\Users\User;
use App\Models\User\Subjects;


class SelectNameDetails implements DisplayUsers{

  // 改修課題：選択科目の検索機能
  public function resultUsers($keyword, $category, $updown, $gender, $role, $subjects){
    if(is_null($gender)){
      $gender = ['1', '2', '3'];
    }else{
      $gender = array($gender);
    }
    if(is_null($role)){
      $role = ['1', '2', '3', '4'];
    }else{
      $role = array($role);
    }
    if(empty($subjects)){
      $subjects = Subjects::pluck('id')->toArray(); //必要なカラムのデータだけ取り出す　Subject（ユーザーのsubject 単数）
    }elseif (!is_array($subjects)) { //変数 $subjects が配列ではない場合に true を返す
      $subjects = explode(',', $subjects); // explode(',', $subjects)subjects文字列の場合カンマで分割し、配列に変換 ['1', '2', '3']
      //dd($subjects);
    }
    $users = User::with('subjects')
    ->where(function($q) use ($keyword){
      $q->Where('over_name', 'like', '%'.$keyword.'%')
      ->orWhere('under_name', 'like', '%'.$keyword.'%')
      ->orWhere('over_name_kana', 'like', '%'.$keyword.'%')
      ->orWhere('under_name_kana', 'like', '%'.$keyword.'%');
    })
    ->where(function($q) use ($role, $gender){
      $q->whereIn('sex', $gender)
      ->whereIn('role', $role);
    })
    ->whereHas('subjects', function($q) use ($subjects){
      $q->whereIn('subjects.id', $subjects);
    })
    ->orderBy('over_name_kana', $updown)->get();
    return $users;
  }

}
