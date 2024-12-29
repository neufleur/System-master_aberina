<?php

namespace App\Models\Users;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Like;



use Auth;

class User extends Authenticatable
{
    use Notifiable;
    use softDeletes;

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
        return $this->hasMany('App\Models\Posts\Post');
    }

    public function calendars(){
        return $this->belongsToMany('App\Models\Calendars\Calendar', 'calendar_users', 'user_id', 'calendar_id')->withPivot('user_id', 'id');
    }

    public function reserveSettings(){
        return $this->belongsToMany('App\Models\Calendars\ReserveSettings', 'reserve_setting_users', 'user_id', 'reserve_setting_id')->withPivot('id');
    }
//usersテーブルとsubjectsテーブルのリレーション
    public function subjects(){
        return $this->belongsToMany(Subjects::class, 'user_subject', 'user_id', 'subject_id');
        // return $this->belongsToMany('⓵Subjectsの場所', '⓶中間テーブル', '⓷自分のidが入る' ④相手モデルに関係しているid);
    }

    // いいねしているかどうか
    public function is_Like($post_id){
        return Like::where('user_id', Auth::id())->where('post_id', $post_id)->first(['id']);
    }

    public function likePostId(){
        return Like::where('like_user_id', Auth::id());
    }
//いいね
    public function likes() {
         return $this->hasMany(Like::class,'like_user_id');
        }
         //この投稿に対して既にlikeしたかどうかを判別する
    public function isLike($postId)
    {
      return $this->likes()->where('like_post_id',$postId)->exists();
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

//ログイン済み、且つadmin権限を持つユーザーのみが閲覧できるページを作成⓵
public function isTeacher() {
   return in_array($this->role, [1, 2, 3]); // 教師の役職を配列で定義
}

public function isAdmin() {
    return $this->role === 4;
}
}
