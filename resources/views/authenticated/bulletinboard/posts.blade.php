@extends('layouts.sidebar')

@section('content')
<div class="board_area w-100 d-flex">
  <div class="post_view w-75 mt-5">
    <p class="w-75">投稿一覧</p>
    @foreach($posts as $post)
    <div class="post_area border w-80 p-3">
      <p class="post-user"><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
      <p class="post-title"><a href="{{ route('post.detail', ['id' => $post->id]) }}" class="post-title">{{ $post->post_title }}</a></p>
        <!-- 投稿の選択したサブカテゴリー表示 -->
      <div class="post_bottom_area d-flex">
        <div class="d-flex post_status">
        <div class="post_sub_category">
          <ul>
            <!-- foreachで現在表示している投稿に関連するサブカテゴリーを取得 subCategoriesはリレーションで定義されたbelongsToMany() -->
          @foreach($post->subCategories as $subCategory)
            <li class="sub_category" sub_category_id="{{ $subCategory->id }}">
            <span>{{ $subCategory->sub_category }}</span></li>
            <!-- $subCategoryはサブカテゴリーの1つのレコード sub_categoryカラム-->
          @endforeach
            </ul>
            </div>
            </div>
          <!-- いいねコメント -->
            <div class="comment_like">
          <div class="counts_comment">
            <i class="fa fa-comment"></i><span class="">{{ $post->comments_count }}</span>
          </div>
          <div class="counts_like">
            @if(Auth::user()->is_Like($post->id))
            <p class="m-0"><i class="fas fa-heart un_like_btn" post_id="{{ $post->id }}"></i>
            <span class="like_counts like_counts{{ $post->id }}">{{ $post->like_count }}</span>

            @else
            <p class="m-0"><i class="fas fa-heart like_btn" post_id="{{ $post->id }}"></i>
           <span class="like_counts like_counts{{ $post->id }}">{{ $post->like_count }}</span>

            @endif
          </div>
        
        </div>
      </div>
    </div>
    @endforeach
  </div>
  <div class="other_area">
    <div class="">
      <div class="post_create"><a href="{{ route('post.input') }}">投稿</a></div>
      <div class="">
        <input type="text" class="post_search p-10" placeholder="キーワードを検索" name="keyword" form="postSearchRequest" ><input type="submit"  class="post_search_button" value="検索" form="postSearchRequest">
      </div>
      <input type="submit" name="like_posts" class="category_btn_like" value="いいねした投稿" form="postSearchRequest">
      <input type="submit" name="my_posts" class="category_btn_my" value="自分の投稿" form="postSearchRequest">
          <ul>
            <p class="categories">カテゴリー検索</p>
        @foreach($categories as $category)
        <!-- カテゴリー全てforeachで呼び出す -->
        <li class="main_categories" category_id="{{ $category->id }}"><span>{{ $category->main_category }}</span></li>
         </ul>
          <ul>
            <!-- メインの中にサブ表示できるように -->
           @foreach($category->subCategories as $subCategory)
           <!--  $categoryに関連付けられた全てのサブカテゴリーの各サブカテゴリーを$subCategory変数に入れる -->
        <li class="sub_category_search" sub_category_id="{{ $subCategory->id }}">
        <a href="{{ route('post.show', ['category_word' => $subCategory->id]) }}">
          <span>{{ $subCategory->sub_category }}</span></a></li>
            @endforeach
            </ul>
        @endforeach
        </li>
    </div>
  </div>
  <form action="{{ route('post.show') }}" method="get" id="postSearchRequest"></form>
</div>
@endsection