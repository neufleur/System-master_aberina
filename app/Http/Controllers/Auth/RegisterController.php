<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\Users\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

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
    protected $redirectTo = RouteServiceProvider::HOME;

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

    public function registerPost(Request $request)
    {
     if ($request->isMethod ('post') ){

        $rulus = [
            'over_name' => 'required|max:10|string',
            'under_name' => 'required|max:10|string',
            'over_name_kana' => 'required|max:30|regex:/^[ァ-ヴーヶー]+$/u|string',
            'under_name_kana' =>'required|max:30|regex:/^［ァ-ヴーヶー]+$/u|string',
            'mail' => 'required|email|min:5|max:100|unique:users,mail',
            'sex' =>' required|in:male,female,other',
            'old _year'=> 'required|date_format:Y-m-d|before:'. Carbon:: today () ->toDateString(),
            'role'=>'required|in:国語講師,数学講師,英語教師,生徒',
            'password' =>'required|alpha_num|min:8|max:30|confirmed|string',
                 ];
        $message = [
            'over_name.required'=>'名前は必ず入力してください。',
            'over_name.max'=>'名前は10文字以下で入力してください。',
            'under_name.required'=>'名前は必ず入力してください。',
            'under_name.max'=>'名前は10文字以下で入力してください。',
            'over_name_kana.required'=>'カタカナで必ず入力してください。',
            'over_name_kana.max'=>'カタカナは30文字以下で入力してください。',
            'under_name_kana.required'=>'カタカナで必ず入力してください。',
            'under_name_kana.max'=>'カタカナは30文字以下で入力してください。',
            'mail.required'=>'メールアドレスは入力必須です。',
            'mail.email'=>'有効なメールアドレスを入力してください。',
            'mail.unique:users,mail' =>'このメールアドレスは既に使われています。',
            'mail.max'=>'メールアドレスは100文字以下で入力してください。',
            'sex'=>'性別は必須項目です。',
            'old_year'=>'生年月日は必ず入力してください。',
            'password. required'=>'パスワードは入力必須です。',
            'password.min'=>'パスワードは8文字以上、30文字以下で入力してください。',
            'password.max'=>'パスワードは8文字以上、30文字以下で入力してください。',
            'password.confirmed'=>'確認パスワードが一致していません。',
            'password.alpha_num'=>'パスワードは英数字で入力してください。',
                 ];

                 $validate = Validator::make($request->all(), $rulus, $message, );//バリデーションを実行

                 DB::beginTransaction();
        
         if ($validate->fails ()) {
            return redirect ('/register')
            //エラーを返すか、エラーとともにリダイレクトする
            -> withInput () // セッション()に入力値すべてを入れる
            ->withErrors($validate);// セッション(errors）にエラーの情報を入れる
            }try{
                $old_year = $request->old_year;
                $old_month = $request->old_month;
                $old_day = $request->old_day;
                $data = $old_year . '-' . $old_month . '-' . $old_day;
                $birth_day = date('Y-m-d', strtotime($data));
                $subjects = $request->subject;

            $user_get = User::create([
                'over_name' => $request->over_name,
                'under_name' => $request->under_name,
                'over_name_kana' => $request->over_name_kana,
                'under_name_kana' => $request->under_name_kana,
                'mail_address' => $request->mail_address,
                'sex' => $request->sex,
                'birth_day' => $birth_day,
                'role' => $request->role,
                'password' => bcrypt($request->password)
            ]);
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
}