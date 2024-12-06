<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\Users\User;
use Illuminate\Support\Facades\Log;
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
            'under_name_kana' => 'required|max:30|regex:/^[ァ-ヴーヶー]+$/u|string',
            'mail_address' => 'required|email|min:5|max:100|unique:users,mail_address',
            'sex' => 'required|in:1,2,3',
            'old_year'=> 'required||digits:4|before:'. Carbon:: today () ->toDateString(),
            'old_month'=> 'required',
            'old_day'=> 'required',
            'role'=>'required|in:1,2,3,4',
            'password' =>'required|alpha_num|min:8|max:30|confirmed|string',
                 ];
        $message = [
            'over_name.required'=>'名前は必ず入力してください。',
            'over_name.max'=>'名前は10文字以下で入力してください。',
            'under_name.required'=>'名前は必ず入力してください。',
            'under_name.max'=>'名前は10文字以下で入力してください。',
            'over_name_kana.required'=>'カタカナで必ず入力してください。',
            'over_name_kana.max'=>'カタカナは30文字以下で入力してください。',
            'over_name_kana.regex' => 'カタカナのみで入力してください。',
            'under_name_kana.required'=>'カタカナで必ず入力してください。',
            'under_name_kana.max'=>'カタカナは30文字以下で入力してください。',
            'under_name_kana.regex' => 'カタカナのみで入力してください。',
            'mail_address.required'=>'メールアドレスは入力必須です。',
            'mail_address.email'=>'有効なメールアドレスを入力してください。',
            'mail_address.unique:users,mail' =>'このメールアドレスは既に使われています。',
            'mail_address.max'=>'メールアドレスは100文字以下で入力してください。',
            'sex'=>'性別は必須項目です。',
            'sex.in' => '選択された性別が無効です。',
            'old_year.required' => '生年月日は必ず入力してください。',
            'old_year.digits' => '生年月日は4桁の年で入力してください。',
            'old_year.before' => '生年月日は今日より前の日付を入力してください。',
            'old_month.required' => '月は必ず入力してください。',
            'old_day.required' => '日は必ず入力してください。',
            'role'=>'役職を選択してください。',
            'password. required'=>'パスワードは入力必須です。',
            'password.min'=>'パスワードは8文字以上、30文字以下で入力してください。',
            'password.max'=>'パスワードは8文字以上、30文字以下で入力してください。',
            'password.confirmed'=>'確認パスワードが一致していません。',
            'password.alpha_num'=>'パスワードは英数字で入力してください。',
                 ];

                 $validate = Validator::make($request->all(), $rulus, $message, );//バリデーションを実行


         if ($validate->fails ()) {
            return redirect ('/register')
            //エラーを返すか、エラーとともにリダイレクトする
            -> withInput () // セッション()に入力値すべてを入れる
            ->withErrors($validate);// セッション(errors）にエラーの情報を入れる
            }try{
                DB::beginTransaction(); //トランザクションとは、一連のデータベース操作がすべて成功するか、すべて失敗するかを保証するためのメカニズム
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

            // ユーザー作成が成功したか確認　$user_getがnullでないことを確認
            if ($user_get) {
            $user = User::findOrFail($user_get->id); //findOrFail 一致するidが見つからなかった場合は、エラーを返します。

            $user->subjects()->attach($subjects);
            DB::commit();
            return view('auth.login.login');
            }else{
                 // ユーザー作成に失敗した場合
            DB::rollback();
            return redirect('/register');
            }
    }catch(\Exception $e){ //\Exception $e 例外処理
        // ログにエラーを記録
        DB::rollback(); //何らかのエラーが発生した場合、すべての変更を元に戻すためにロールバックします
            return redirect()->route('loginView');
        }
    }
}
}