<?php

namespace App\Http\Controllers\Authenticated\Calendar\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\Admin\CalendarView;
use App\Calendars\Admin\CalendarSettingView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\Users\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

//スクール予約確認画面
class CalendarsController extends Controller
{
    public function show(){
        $calendar = new CalendarView(time());
        // $calendar = new CalendarView(Carbon::now()->format('Y-m-01'));//私が追加　現在の月を表示
        return view('authenticated.calendar.admin.calendar', compact('calendar'));
    }

    public function reserveDetail($date, $part){
        //setting_reserve,setting_part指定された日時部数と一致するものを取得
        $reservePersons = ReserveSettings::with('users')->whereDate('setting_reserve',$date)->where('setting_part', $part)->get();
        // dd($reservePersons);
        // setting_reserveの日付を年月日の形式に変換
        foreach ($reservePersons as $reservePerson) {
        $reservePerson->formatted_reserve_date = Carbon::parse($reservePerson->setting_reserve)->format('Y年m月d日');
    }
        return view('authenticated.calendar.admin.reserve_detail', compact('reservePersons', 'date', 'part'));
    }

    public function reserveSettings(){
        $calendar = new CalendarSettingView(time());
        return view('authenticated.calendar.admin.reserve_setting', compact('calendar'));
    }

    public function updateSettings(Request $request){
        $reserveDays = $request->input('reserve_day');
        foreach($reserveDays as $day => $parts){
            foreach($parts as $part => $frame){
                ReserveSettings::updateOrCreate([
                    'setting_reserve' => $day,
                    'setting_part' => $part,
                ],[
                    'setting_reserve' => $day,
                    'setting_part' => $part,
                    'limit_users' => $frame,
                ]);
            }
        }
        return redirect()->route('calendar.admin.setting', ['user_id' => Auth::id()]);
    }

    
}
