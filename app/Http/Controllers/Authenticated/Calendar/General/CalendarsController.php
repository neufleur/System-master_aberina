<?php

namespace App\Http\Controllers\Authenticated\Calendar\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\General\CalendarView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\Users\User;
use Auth;
use Carbon\Carbon;
use DB;
//スクール予約画面
class CalendarsController extends Controller
{
    public function show(){
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.general.calendar', compact('calendar'));
    }

    public function reserve(Request $request){
        DB::beginTransaction();
        try{
            $getPart = $request->getPart;
            $getDate = $request->getData;
            // $getPart と $getDate が null の場合、空の配列を使用する
            $getPart = $getPart ?? [];
            $getDate = $getDate ?? [];

            // dd($getPart, $getDate);
            // 配列の長さが異なる場合、空の配列を返す
        if (count($getDate) !== count($getPart)) {
            $reserveDays = [];  // 長さが一致しない場合は空配列を設定
        } else {
            // 長さが一致する場合にarray_combineを使用
            $reserveDays = array_filter(array_combine($getDate, $getPart));
        }
            $reservedDates = []; // 予約日を格納する配列
dd($reservedDates);
            foreach($reserveDays as $key => $value){
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first();
                if ($reserve_settings) {
                $reserve_settings->decrement('limit_users');
                $reserve_settings->users()->attach(Auth::id());
                $reservedDates[] = $key; // 予約日を保存
            }
            }
            // 予約日をセッションに保存
            $request->session()->put('reserved_dates', $reservedDates);
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }
// // 予約済みの日付と部数をカレンダーに渡す
// $reservedCounts = ReserveSettings::whereIn('setting_reserve', $reservedDates)
// ->select('setting_reserve', DB::raw('count(*) as reserved_count'))
// ->groupBy('setting_reserve')
// ->pluck('reserved_count', 'setting_reserve');

// return redirect()->route('calendar.general.show', ['user_id' => Auth::id()])
// ->with('reservedCounts', $reservedCounts);  // 予約された部数をビューに渡す
// }
}

//システムで取り扱うデータを全部入れるのがデータベース　改修課題のデータベース
//テーブル　データベースの中にある表
//レコード一行のこと　カラム縦の列　フィールドが1個のデータ

