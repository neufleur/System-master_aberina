<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    //多対多のリレーション　間テーブルと繋げる　
    public function likes()
    {
        return $this->belongsToMany('App\Models\Post','likes','user_id','post_id')->withTimestamps();
        //return $this->belongsToMany('⓵postの場所', '⓶中間テーブル', '⓷いいねしたユーザーidが入る' ④いいねされたpost_id;
    }

    //この投稿に対して既にlikeしたかどうかを判別する
    public function isLike($postId)
    {
      return $this->likes()->where('post_id',$postId)->exists();
    }

    //isLikeを使って、既にlikeしたか確認したあと、いいねする（重複させない）
    public function like($postId)
    {
      if($this->isLike($postId)){
        //もし既に「いいね」していたら何もしない
      } else {
        $this->likes()->attach($postId);
      }
    }

    //isLikeを使って、既にlikeしたか確認して、もししていたら解除する
    public function unlike($postId)
    {
      if($this->isLike($postId)){
        //もし既に「いいね」していたら消す
        $this->likes()->detach($postId);
      } else {
      }
    }
}
