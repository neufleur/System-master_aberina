<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;
use App\Models\Posts\PostComment;
use App\Models\Categories\SubCategory;
use App\Models\Categories\MainCategory;
class Post extends Model
{


    const UPDATED_AT = null;
    const CREATED_AT = null;
    

    protected $fillable = [
        'user_id',
        'post_title',
        'post',
        'comment',
        'sub_category_id',
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
        // リレーションの定義　多対多
        return $this->belongsToMany(SubCategory::class, 'post_sub_categories', 'post_id', 'sub_category_id');

    }

    public function subCategory()
{
    return $this->belongsTo(SubCategory::class, 'sub_category_id');
}

    // コメント数
    public function commentCounts($post_id){
        return Post::with('postComments')->find($post_id)->postComments();
    }


}