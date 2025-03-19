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
        //コメントのリクエストが許可
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
            'comment' => 'required|max:250|string',
        ];
    }

    public function messages(){
        return [
            'comment.required' => 'コメントは必ず入力してください。',
            'comment.max' => 'コメントは250文字以内で入力してください。',
        ];
    }
}
