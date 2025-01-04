<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post; // ここで Post クラスをインポート
use App\Models\User\Users;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PostCommentsRequest;
use App\Http\Requests\PostEditRequest;

class PostsController extends Controller
{

     //public function 関数(引数)引数　とは関数に渡して処理の中でその値を使うことができるもの

    public function show(Request $request){
        $posts = Post::with('user', 'postComments')->get();
        $categories = MainCategory::get();
        $like = new Like;
        $post_comment = new Post;
        $user = new User;
        if(!empty($request->keyword)){
            $posts = Post::with('user', 'postComments')
            ->where('post_title', 'like', '%'.$request->keyword.'%')
            ->orWhere('post', 'like', '%'.$request->keyword.'%')->get();
        }else if($request->category_word){
            $sub_category = $request->category_word;
            $posts = Post::with('user', 'postComments')->get();
        }else if($request->like_posts){
            $likes = Auth::user()->likePostId()->get('like_post_id');
            $posts = Post::with('user', 'postComments')
            ->whereIn('id', $likes)->get();
        }else if($request->my_posts){
            $posts = Post::with('user', 'postComments')
            ->where('user_id', Auth::id())->get();
        }
        // ここで各投稿の「いいね」数 コメント数を取得　コメント数Post.phpで指定したメソッドpostComments
        foreach ($posts as $post) {
            $post->like_count = Like::likeCounts($post->id);
            $post->comments_count = $post->postComments()->count();
        }

        return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'like', 'post_comment'));
    }

    public function postDetail($post_id){
        $post = Post::with('user', 'postComments')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }

    public function postInput(){
        $main_categories = MainCategory::get();
        return view('authenticated.bulletinboard.post_create', compact('main_categories'));
    }

    public function postCreate(PostFormRequest $request){
        //dd($request->all());
        $post = Post::create([
            'user_id' => Auth::id(),
            'post_title' => $request->post_title,
            'post_body' => $request->post_body,
            'post' => '',
        ]);
        return redirect()->route('post.show');
    }

    public function postEdit(PostEditRequest $request){

        Post::where('id', $request->post_id)->update([
            'post_title' => $request->post_title,
            'post_body' => $request->post_body,
        ]);
        //dd($request->post_title);
        //dd($request->post_body);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function postDelete($id){
        Post::findOrFail($id)->delete();
        return redirect()->route('post.show');
    }
    public function mainCategoryCreate(Request $request){
        MainCategory::create(['main_category' => $request->main_category_name]);
        return redirect()->route('post.input');
    }

    public function commentCreate(PostCommentsRequest $request){
        //引数PostCommentsRequestの中からrequest
        $validatedData = $request->validated();
        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'comment' => $validatedData['comment'],
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
        //リダイレクト先となるルートに付けられた名前 post.detail'  ['id' => $request->post_id] は、ルートパラメータに値を渡している
    }

    public function myBulletinBoard(){
        $posts = Auth::user()->posts()->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }

    public function likeBulletinBoard(){
        $like_post_id = Like::with('users')->where('like_user_id', Auth::id())->get('like_post_id')->toArray();
        $posts = Post::with('user')->whereIn('id', $like_post_id)->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }
//いいねする
    public function postLike(Request $request,$id){
        $user_id = Auth::id();
        $post_id = $id;

        $like = new Like;

        $like->like_user_id = $user_id;
        $like->like_post_id = $post_id;
        $like->save();

        return redirect()->back();
    }
//いいね外す
    public function postUnLike(Request $request,$id){
        $user_id = Auth::id();
        $post_id = $id;

        $like = new Like;

        $like->where('like_user_id', $user_id)
             ->where('like_post_id', $post_id)
             ->delete();

             return redirect()->back();
    }
}

