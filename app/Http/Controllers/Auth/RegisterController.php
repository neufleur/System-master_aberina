<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Users\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Support\Facades\Log;
use DB;
use App\Models\Users\Subjects;

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
        //  Log::info('Request data:', $request->all()); // これを追加してリクエストデータをログに記録
                DB::beginTransaction(); //トランザクションとは、一連のデータベース操作がすべて成功するか、すべて失敗するかを保証するためのメカニズム
                try{
                $old_year = $request->old_year;
                $old_month = $request->old_month;
                $old_day = $request->old_day;
                $data = $old_year . '-' . $old_month . '-' . $old_day;
                $birth_day = date('Y-m-d', strtotime($data));
                $subjects = $request->subject;
                Log::info('Subjects from request: ', ['subjects' => $subjects]);
// dd($subjects);
            $user_get = User::create([
                'over_name' => $request->over_name,
                'under_name' => $request->under_name,
                'over_name_kana' => $request->over_name_kana,
                'under_name_kana' => $request->under_name_kana,
                'mail_address' => $request->mail_address,
                'sex' => $request->sex,
                'birth_day' => $birth_day,
                'role' => $request->teacher,
                'password' => bcrypt($request->password)
            ]);
            // dd($user_get);

                //    Log::info('User created: ', ['user' => $user_get]);
                   $user = User::findOrFail($user_get->id);
                   $user->subjects()->attach($subjects);
                   DB::commit();
                   return view('auth.login.login');
               }catch(\Exception $e){
                   DB::rollback();
                   return redirect()->route('loginView');
               }
           }
}
