<?php

namespace App\Http\Requests\BulletinBoard;

use Illuminate\Foundation\Http\FormRequest;

class PostFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'post_title' => 'required|max:100|string',
            'post_body' => 'required|max:500|string',
        ];
    }

    public function messages(){
        return [
            'post_title.required' => 'タイトルは入力必須です。',
            'post_title.max' => 'タイトルは100文字以内で入力してください。',
            'post_body.required' => '投稿内容は入力必須です。',
            'post_body.max' => '最大文字数は500文字です。',
        ];
    }
}
