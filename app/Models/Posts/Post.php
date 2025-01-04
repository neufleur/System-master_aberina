<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;
use App\Models\Posts\PostComment;

class Post extends Model
{


    const UPDATED_AT = null;
    const CREATED_AT = null;
    

    protected $fillable = [
        'user_id',
        'post_title',
        'post_body',
        'post',
        'comment',
    ];

    public function user(){
        return $this->belongsTo(User::class);
        //return $this->belongsTo('App\Models\Users\User');
    }

    //一対多　コメント数表示　PostCommentから呼び出す
    public function postComments(){
        return $this->hasMany(PostComment::class);
    }

    public function subCategories(){
        // リレーションの定義
    }

    // コメント数
    public function commentCounts($post_id){
        return Post::with('postComments')->find($post_id)->postComments();
    }


}