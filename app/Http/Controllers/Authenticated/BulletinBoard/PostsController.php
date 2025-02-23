<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post; // ここで Post クラスをインポート
use App\Models\Users\User;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PostCommentsRequest;
use App\Http\Requests\PostEditRequest;
use App\Http\Requests\PostSearchRequest;
use Illuminate\Support\Facades\Log;
class PostsController extends Controller
{

     //public function 関数(引数)引数　とは関数に渡して処理の中でその値を使うことができるもの

    public function show(Request $request){
        $posts = Post::with('user','subCategories',  'postComments')->get();
        $categories = MainCategory::with('subCategories')->get();
        $like = new Like;
        $post_comment = new Post;
        $user = new User;
        if(!empty($request->keyword)){
            $posts = Post::with('user', 'subCategories', 'postComments')
            ->where('post_title', 'like', '%'.$request->keyword.'%')
            ->orWhere('post', 'like', '%'.$request->keyword.'%')->get();
        }else if($request->category_word){
            $sub_category = $request->category_word;
            $posts = Post::with('user','subCategories',  'postComments')->get();
        }else if($request->like_posts){
            $likes = Auth::user()->likePostId()->get('like_post_id');
            $posts = Post::with('user', 'subCategories', 'postComments')
            ->whereIn('id', $likes)->get();
        }else if($request->my_posts){
            $posts = Post::with('user','subCategories',  'postComments')
            ->where('user_id', Auth::id())->get();
        }
        // ここで各投稿の「いいね」数 コメント数を取得　コメント数Post.phpで指定したメソッドpostComments
        foreach ($posts as $post) {
            $post->like_count = Like::likeCounts($post->id);
            $post->comments_count = $post->postComments()->count();
        }
        // dd($categories);
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
            'post' => $request->post_body,
        ]);
        return redirect()->route('post.show');
    }

    public function postEdit(PostEditRequest $request){

        Post::where('id', $request->post_id)->update([
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);
        //dd($request->post_title);
        //dd($request->post);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function postDelete($id){
        Post::findOrFail($id)->delete();
        return redirect()->route('post.show');
    }
    public function mainCategoryCreate(PostFormRequest $request){
        MainCategory::create(['main_category' => $request->main_category_name]);
        return redirect()->route('post.input');
    }
    public function subCategoryCreate(PostFormRequest $request){ //main_category_id も一緒に保存する
        Log::debug('リクエストデータ:', $request->all()); // デバッグログ出力
        SubCategory::create(['sub_category' => $request->sub_category_name, 'main_category_id' => $request->main_category_id]);
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
    public function postLike(Request $request, Post $post){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->like_user_id = $user_id;
        $like->like_post_id = $post_id;
        $like->save();

        return response()->json(); //json()は成功したレスポンスを返したい場合に使う
    }
//いいね外す
    public function postUnLike(Request $request, Post $post){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->where('like_user_id', $user_id)
             ->where('like_post_id', $post_id)
             ->delete();

             return response()->json();
    }

    //投稿検索
    public function postSearch(Request $request){
        $posts = Post::get();
        $keyword = $request->input('keyword'); //キーワードを取得
        if(!empty($keyword)){
            if (!empty($keyword)) {
                $posts->where('post_title', 'LIKE', "%" . $keyword . "%")
                      ->orWhere('post_body', 'LIKE', "%" . $keyword . "%")
                      ->orWhereHas('subCategories', function($query) use ($keyword) {
                        // サブカテゴリー名の完全一致検索
                        $query->where('sub_category', $keyword);
                      });
                    }

                    $posts = $posts->get();
        
        return view('authenticated.bulletinboard.posts', compact('posts','keyword'));
    }
}
}
