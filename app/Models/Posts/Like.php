<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;



class Like extends Model
{

    protected $fillable = [
        'like_user_id',
        'like_post_id',
];
const UPDATED_AT = null;
    //多対多のリレーション　間テーブルと繋げる　
    public function posts()
    {
        return $this->belongsToMany(Post::class,'likes','like_user_id','like_post_id');
        //return $this->belongsToMany('⓵postの場所', '⓶中間テーブル', '⓷いいねしたユーザーidが入る' ④いいねされたpost_id;
    }
    public static function likeCounts($like_post_id)
    {
        return Like::where('like_post_id', $like_post_id)->count();
    }

   
}
