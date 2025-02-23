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
        $routeName = $this->route()->getName();//main.categoryのみにバリデーション
        if ($routeName === 'main.category.create') {
            return [
                'main_category_name' => 'required|max:100|string|unique:main_categories,main_category',
            ];
        }
        if ($routeName === 'sub.category.create') {
            return [
                'main_category_id' => 'required|max:100|integer|exists:main_categories,id',
                'sub_category_name'=> 'required|max:100|string|unique:sub_categories,sub_category',
            ];
        }
        return [
            'post_title' => 'required|max:100|string',
            'post_body' => 'required|max:500|string',
        ];
    }

    public function messages(){
        $routeName = $this->route()->getName();
        if ($routeName === 'main.category.create')  {
            return [
                'main_category_name.required' => 'メインカテゴリー名は入力必須です。',
                'main_category_name.max' => 'メインカテゴリー名は100文字以内で入力してください。',
                'main_category_name.string' => 'メインカテゴリー名は文字列で入力してください。',
                'main_category_name.unique' => 'このメインカテゴリー名は既に登録されています。',
            ];
        }
        if  ($routeName === 'sub.category.create') {
            return [
                'main_category_id.required' => 'メインカテゴリーを選択してください。',
                'main_category_id.integer' => 'メインカテゴリーの形式が正しくありません。',
                'main_category_id.exists' => '選択されたメインカテゴリーが存在しません。',
                'sub_category_name.required' => 'サブカテゴリーは入力必須です。',
                'sub_category_name.max' => 'サブカテゴリーは100文字以内で入力してください。',
                'sub_category_name.unique' => 'このサブカテゴリーは既に登録されています。',
            ];
        }
        return [
            'post_title.required' => 'タイトルは入力必須です。',
            'post_title.max' => 'タイトルは100文字以内で入力してください。',
            'post_body.required' => '投稿内容は入力必須です。',
            'post_body.max' => '最大文字数は500文字です。',
        ];
    }
}
