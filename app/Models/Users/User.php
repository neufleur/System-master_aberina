<?php

namespace App\Models\Users;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Posts\Like;
use App\Models\Posts\Post;
use App\Models\Users\Subjects;



class User extends Authenticatable
{
    use Notifiable;

    const CREATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'over_name',
        'under_name',
        'over_name_kana',
        'under_name_kana',
        'mail_address',
        'sex',
        'birth_day',
        'role',
        'subject',
        'password'
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts(){
        return $this->hasMany(Post::class);
    }

    public function calendars(){
        return $this->belongsToMany('App\Models\Calendars\Calendar', 'calendar_users', 'user_id', 'calendar_id')->withPivot('user_id', 'id');
    }

    public function reserveSettings(){
        return $this->belongsToMany('App\Models\Calendars\ReserveSettings', 'reserve_setting_users', 'user_id', 'reserve_setting_id')->withPivot('id');
    }
//usersテーブルとsubjectsテーブルのリレーションbelongsToMany多対多のこと
    public function subjects(){
        return $this->belongsToMany(Subjects::class, 'subject_users', 'user_id', 'subject_id');
        // return $this->belongsToMany('⓵Subjectsの場所', '⓶中間テーブル', '⓷自分のidが入る' ④相手モデルに関係しているid);
    }

        //いいねしているかどうかを判定
    public function is_Like($post_id) {
        return $this->likes()->where('like_post_id', $post_id)->exists();
    }
        //ユーザーがしたいいねlikesテーブルのデータを取得 ログインユーザ-idとlikesテーブルのlike_user_idを紐づけ
    public function likes() {
         return $this->hasMany(Like::class, 'like_user_id');
        }
        //いいねしたlikesレコード取得
        public function likedPosts(){ //ユーザーが「いいね」した投稿のデータを取得するidがlike_user_idに一致するlikesレコードを取得
            return $this->hasMany(Like::class, 'like_user_id', 'id');
        }

        // いいねした投稿のIDだけを取得
        public function likePostIds() {
            return $this->hasMany(Like::class, 'like_user_id')->pluck('like_post_id'); // 'like_post_id' カラムを取得
        }
//ログイン済み、且つadmin権限を持つユーザーのみが閲覧できるページを作成⓵
public function isTeacher() {
   return in_array($this->role, [1, 2, 3]); // 教師の役職を配列で定義
}

public function isAdmin() {
    return $this->role === 4;
}
}
