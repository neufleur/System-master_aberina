<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostCommentsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'post_comments' => 'required|max:250|string',
        ];
    }

    public function messages(){
        return [
            'post_comments.required' => 'コメントは入力必須です。',
            'post_comments.max' => 'コメントは250文字以内で入力してください。',
        ];
    }
}
