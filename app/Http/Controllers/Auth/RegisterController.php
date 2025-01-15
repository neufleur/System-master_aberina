<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Support\Facades\Log;
use DB;
use App\Models\Users\Subjects;
use Carbon\Carbon;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/register';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    public function registerView()
    {
        $subjects = Subjects::all();
        return view('auth.register.register', compact('subjects'));
    }

    public function registerPost(RegisterUserRequest $request)
    {

        //Log::info('Request data:', $request->all()); // これを追加してリクエストデータをログに記録
                DB::beginTransaction(); //トランザクションとは、一連のデータベース操作がすべて成功するか、すべて失敗するかを保証するためのメカニズム
                try{
                // 年月日を1つの日付にまとめる
                $birth_day = date('Y-m-d', strtotime($request->old_year . '-' . $request->old_month . '-' . $request->old_day));
                $subjects = $request->subject;
                // dd($request->all());
            //    dd($birth_day);
            $user_get = User::create([
                'over_name' => $request->over_name,
                'under_name' => $request->under_name,
                'over_name_kana' => $request->over_name_kana,
                'under_name_kana' => $request->under_name_kana,
                'mail_address' => $request->mail_address,
                'sex' => $request->sex,
                'birth_day' => $birth_day,
                'role' => $request->role,
                'password' => bcrypt($request->password),
            ]);
            // Log::info('User created:', ['user' => $user_get]);
            // dd($user_get);

                   $user = User::findOrFail($user_get->id);
                   $user->subjects()->attach($subjects); //$user=85行目のことを指す　subjects()はUser.phpに書かれている68行目のsubjectsメソッド　attachデーターを追加する
                   DB::commit();
                   return view('auth.login.login');
               }catch(\Exception $e){
                   DB::rollback();
                   return redirect()->route('loginView');
               }
           }
}
