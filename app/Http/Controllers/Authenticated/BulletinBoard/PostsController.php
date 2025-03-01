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
use Illuminate\Support\Facades\Log;
class PostsController extends Controller
{

     //public function 関数(引数)引数　とは関数に渡して処理の中でその値を使うことができるもの

    public function show(Request $request){
        // dd($request->sub_category_id);
        $posts = Post::with('user','subCategories',  'postComments')->get();
        $categories = MainCategory::with('subCategories')->get();
        $like = new Like;
        $post_comment = new Post;
        $user = new User;
        if(!empty($request->keyword)){ //投稿タイトル 本文 サブカテゴリーで検索
            $keyword = $request->keyword;
            $posts = Post::with('user', 'subCategories', 'postComments')
            ->where('post_title', 'like', '%'.$request->keyword.'%')
            ->orWhere('post', 'like', '%'.$request->keyword.'%')
            ->orWhereHas('subCategories', function($query) use ($keyword) { //検索条件にサブカテゴリー追加　subCategoriesはPostモデルのリレーション名
                //function($query)はsubCategories(リレーション先のテーブル)に対するキーワード検索指定するため
                $query->where('sub_categories.sub_category', $keyword);//'sub_category'はデータベースのカラム名
            });
            $posts = $posts->get();
        }else if($request->category_word){
            $sub_category = $request->category_word; //リクエストされたcategory_wordを$sub_category(変数)に入れる
            $posts = Post::with('user','subCategories',  'postComments')
            ->whereHas('subCategories', function ($query) use ($sub_category) {   // サブカテゴリー名の完全一致検索WhereHas
                $query->where('sub_categories.id', $sub_category); // IDで完全一致検索->get();で表示させる
            })->get();
        }else if($request->like_posts){
            $likes = Like::where('like_user_id', Auth::id())->pluck('like_post_id'); //いいねしたlike_post_idだけを取得　pluckとは特定のカラムの値だけを取り出すメソッド
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
        // dd( $sub_category_id);
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
        // dd($request->all());
        $post = Post::create([
            'user_id' => Auth::id(),
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);
         // サブカテゴリーが存在する場合にアタッチで紐付け
    if ($request->has('sub_category_id')) {
        $post->subCategories()->attach($request->sub_category_id);
    }
        return redirect()->route('post.show',['post' => $post->id]);
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


}
