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
        return view('authenticated.calendar.general.calendar', compact('calendar'));
    }

    public function reserve(Request $request){
        DB::beginTransaction();
        try{
            //部数日付格納する
            $getDate = $request->getData;
            $getPart = $request->getPart;

            // 素数が異なる場合、不足分を "" で埋めて空欄の日付を同じ数の配列のように埋める
                if (count($getPart) < count($getDate)) {
                    $getPart = array_pad($getPart, count($getDate), "");
                } elseif (count($getPart) > count($getDate)) {
                    $getDate = array_pad($getDate, count($getPart), "");
                }
//フォームから送信された日付の配列$getDateと部数の配列$getPartを組み合わせて
            // $reserveDays = array_combine($getDate, $getPart);
            $reserveDays = array_filter(array_combine($getDate, $getPart));
            // dd($reserveDays);
            foreach($reserveDays as $key => $value){
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first();
                $reserve_settings->decrement('limit_users'); //取得した予約設定レコードの limit_users カラムの値を1減らす
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
        // dd($request->all());
        // dd(ReserveSettings::all()->toArray()); ReserveSettingsのすべてのレコードのカラムの値が配列で表示される
        //ユーザーがキャンセルしたい日付、部数取得
        $reserveDate = $request->input('delete_date');
        $reservePart = $request->input('delete_part');

        //文字列をデータベースの数値と対応させる配列（マッピング）
        $partMapping = ['リモ1部' => 1, 'リモ2部' => 2, 'リモ3部' => 3];
        $reservePartNumber = $partMapping[$reservePart] ?? null; // 対応する数値を取得 なければ null

        $userId = auth()->id();
        $user = User::find($userId);
    
        // 該当する予約が存在するか確if文で確認　キャンセル対象の日付、部数
        if ($user && $reserveDate && $reservePart) {
            // 予約設定を、指定された日付と部数で取得　$reserveDateとデータベースのsetting_reserve一致するかどうか
            $reservationToCancel = ReserveSettings::whereDate('setting_reserve', '=', Carbon::parse($reserveDate)->toDateString())
                ->where('setting_part',$reservePartNumber)
                ->first();
    
            if ($reservationToCancel) {
                // ユーザーとその予約設定との関連を解除する
                $user->reserveSettings()->detach($reservationToCancel->id);
                // キャンセル時に空き枠を増やす
                $reservationToCancel->increment('limit_users');
            }

        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }

}



//システムで取り扱うデータを全部入れるのがデータベース　改修課題のデータベース
//テーブル　データベースの中にある表
//レコード一行のこと　カラム縦の列　フィールドが1個のデータ

