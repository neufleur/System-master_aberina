<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Like; // Like モデルをインポート

class LikeController extends Controller
{
    //
    public function store($postId)
    {
        $like = Like::where('user_id', Auth::id())->where('post_id', $postId)->first();
        if (!$like){
            Auth::user()->like($postId);
        }
        return 'ok!'; //レスポンス内容
    }

    public function destroy($postId)
    {
        $like = Like::where('user_id', Auth::id())->where('post_id', $postId)->first();
        if ($like) {
            Auth::user()->unlike($postId);
        }
        return 'ok!'; //レスポンス内容
    }
}
