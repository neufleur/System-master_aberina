<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;



class Like extends Model
{

    protected $fillable = [
        'like_user_id',
        'like_post_id',
];
    //多対多のリレーション　間テーブルと繋げる　
    public function posts()
    {
        return $this->belongsToMany(Post::class,'likes','like_user_id','like_post_id')->withTimestamps();
        //return $this->belongsToMany('⓵postの場所', '⓶中間テーブル', '⓷いいねしたユーザーidが入る' ④いいねされたpost_id;
    }
    public static function likeCounts($like_post_id)
    { return self::where('like_post_id', $like_post_id)->count();
    }

   
}
