<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;
use App\Models\Users\PostComment;

class Post extends Model
{


    const UPDATED_AT = null;
    const CREATED_AT = null;
    

    protected $fillable = [
        'user_id',
        'post_title',
        'post_body',
        'post',
    ];

    public function user(){
        return $this->belongsTo(User::class);
        //return $this->belongsTo('App\Models\Users\User');
    }

    public function postComments(){
        return $this->hasMany('App\Models\Posts\PostComment');
    }

    public function subCategories(){
        // リレーションの定義
    }

    // コメント数
    public function commentCounts($post_id){
        return Post::with('postComments')->find($post_id)->postComments();
    }

    // public function likes() {
        // return $this->hasMany(Like::class);
    // }
}