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

            // dd([
            //         'getDate' => $getDate,
            //         'getPart' => $getPart,
            //         'is_array(getDate)' => is_array($getDate),
            //         'is_array(getPart)' => is_array($getPart),
            //         'count(getDate)' => count($getDate),
            //         'count(getPart)' => count($getPart),
            //     ]);
                // すでに getDate と getPart の長さが一致しているなら何もしない
                if (count($getPart) < count($getDate)) {// もし$getPartの数が$getDateより少ない場合
                    $getPart = array_pad($getPart, count($getDate), ""); // $getPartを空文字で埋めて$getDateの数に合わせる
                } elseif (count($getPart) > count($getDate)) {
                    $getPart = array_slice($getPart, 0, count($getDate)); // 余分なものはカット
                }
            //foreach で$valueがnullまたは""の場合0に置き換えてる
                foreach ($getPart as $key => $value) {
                    if ($value === null || $value === "") {
                        $getPart[$key] = "0";
                    }
                }

                // dd($getPart);
            //フォームから送信された日付の配列$getDateと部数の配列$getPartを組み合わせて
            // $reserveDays = array_combine($getDate, $getPart);
            $reserveDays = array_filter(array_combine($getDate, $getPart));

            // dd($reserveDays);
            foreach($reserveDays as $key => $value){
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first();
                $reserve_settings->decrement('limit_users'); //取得した予約設定レコードの limit_users カラムの値を1減らす
                $reserve_settings->users()->attach(Auth::id());
            }
            // dd([
            //     'before' => $reserve_settings->limit_users,
            //     'after' => $reserve_settings->limit_users - 1
            // ]);
            // dd($reserve_settings);
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

