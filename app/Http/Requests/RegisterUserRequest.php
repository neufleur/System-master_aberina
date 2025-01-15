<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool //bool 型 (真または偽の値) を返すことを示す　この部分は「戻り値の型宣言」と呼ばれてる
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules():array //array 型 (配列) の値を返すことを示している
    {
        return [
            //
            'over_name' => 'required|max:10|string',
            'under_name' => 'required|max:10|string',
            'over_name_kana' => 'required|max:30|regex:/^[ァ-ヴーヶー]+$/u|string',
            'under_name_kana' => 'required|max:30|regex:/^[ァ-ヴーヶー]+$/u|string',
            'mail_address' => 'required|email|min:5|max:100|unique:users,mail_address',
            'sex' => 'required|in:1,2,3',
            'old_year' => 'required|digits:4',
            'old_month' => 'required',
            'old_day' => 'required',
            // 'birth_day' => 'required|date|before:' . Carbon::today()->toDateString(),
            'role'=>'required|in:1,2,3,4',
            'password' =>'required|alpha_num|min:8|max:30|confirmed|string',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator){
            $year = $this->old_year;
            $month = $this->old_month;
            $day = $this->old_day;
            if (!checkdate($month, $day, $year) || !$this->checkBirthDateRange($year, $month, $day)){
                 $validator->errors()->add('birth_day', '生年月日は2000年1月1日から今日までの正しい日付を入力してください。'); 
            }
        });
    }

    protected function checkBirthDateRange($year, $month, $day){
        $birth_day = strtotime("$year-$month-$day");
        $start_date = strtotime("2000-01-01");
        $end_date = strtotime(date('Y-m-d'));
        return $birth_day >= $start_date && $birth_day <= $end_date;
    }



    public function messages(): array
    {
        return [
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
            'mail_address.unique' =>'このメールアドレスは既に使われています。',
            'mail_address.max'=>'メールアドレスは100文字以下で入力してください。',
            'sex'=>'性別は必須項目です。',
            'sex.in' => '選択された性別が無効です。',
            'birth_day.required' => '生年月日は必ず入力してください。',
            'birth_day.before' => '今日より前の日付を入力してください。',
            'role'=>'役職を選択してください。',
            'password. required'=>'パスワードは入力必須です。',
            'password.min'=>'パスワードは8文字以上、30文字以下で入力してください。',
            'password.max'=>'パスワードは8文字以上、30文字以下で入力してください。',
            'password.confirmed'=>'確認パスワードが一致していません。',
            'password.alpha_num'=>'パスワードは英数字で入力してください。',
        ];
    }

}