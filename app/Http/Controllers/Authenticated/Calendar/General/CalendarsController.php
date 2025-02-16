<?php

namespace App\Http\Controllers\Authenticated\Calendar\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\General\CalendarView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\Users\User;
use Auth;
use DB;
use Carbon\Carbon;


//スクール予約画面
class CalendarsController extends Controller
{
    public function show(){
        //追加　ユーザーが予約した日付と部数を取得
        $calendar = new CalendarView(time());
        // $calendar = new CalendarView(Carbon::now()->format('Y-m-01'));//私が追加　現在の月を表示
        return view('authenticated.calendar.general.calendar', compact('calendar'));
    }

    public function reserve(Request $request){
        DB::beginTransaction();
        try{
            $getPart = $request->getPart;
            $getDate = $request->getData;
            // 要素数が異なる場合、不足分を "" で埋める
if (count($getPart) < count($getDate)) {
    $getPart = array_pad($getPart, count($getDate), "");
} elseif (count($getPart) > count($getDate)) {
    $getDate = array_pad($getDate, count($getPart), "");
}
            $reserveDays = array_filter(array_combine($getDate, $getPart));
            foreach($reserveDays as $key => $value){
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first();
                $reserve_settings->decrement('limit_users');
                $reserve_settings->users()->attach(Auth::id());
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }
    public function delete(Request $request)
    {
        
        $reserveId = $request->input('delete_date');
          // 例として、対象ユーザーのIDが取得できている前提
    $userId = auth()->id();

    // Userモデルのリレーションから対象の予約設定を解除（detach）
    $user = User::find($userId);
    if ($user) {
        $user->reserveSettings()->detach($reserveId);
    }
    return redirect()->back();
        }
        

}



//システムで取り扱うデータを全部入れるのがデータベース　改修課題のデータベース
//テーブル　データベースの中にある表
//レコード一行のこと　カラム縦の列　フィールドが1個のデータ

