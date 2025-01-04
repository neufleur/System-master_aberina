<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

use App\Models\User\Users;

class Subjects extends Model
{
    const UPDATED_AT = null;


    protected $fillable = [
        'name','subject'
    ];

//usersテーブルとsubjectsテーブルのリレーション
    public function users(){
        return $this->belongsToMany(User::class, 'user_subject', 'subject_id', 'user_id');
        // return $this->belongsToMany('⓵Subjectsの場所', '⓶中間テーブル', '⓷自分のidが入る' ④相手モデルに関係しているid);
    }
}