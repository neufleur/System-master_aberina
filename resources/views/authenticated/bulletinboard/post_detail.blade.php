@extends('layouts.sidebar')
@section('content')
<div class="vh-100 d-flex">
  <div class="w-50 mt-5" style="margin-left: 60px;">
    <div class="m-3 detail_container">
      <div class="p-3">
      @if($errors->first('post_title'))
      <span class="error_message">{{ $errors->first('post_title') }}</span>
      @endif
      @if($errors->first('post_body'))
      <span class="error_message">{{ $errors->first('post_body') }}</span>
      @endif

        <div class="detail_inner_head">
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
          <div>
            <!-- ログイン中のユーザーの投稿にのみ編集、削除ボタンを表示 -->
          @if(Auth::user()->id ==$post->user_id)
            <span class="edit-modal-open" post_title="{{ $post->post_title }}" post_body="{{ $post->post }}" post_id="{{ $post->id }}">編集</span>
            <a href="{{ route('post.delete', ['id' => $post->id]) }}" onclick="return confirm('こちらの投稿を削除します。よろしいでしょうか？')" class="delete-post">削除</a>
            @endif
          </div>
        </div>

        <div class="contributor d-flex">
          <p>
            <span>{{ $post->user->over_name }}</span>
            <span>{{ $post->user->under_name }}</span>
            さん
          </p>
          <span class="ml-5">{{ $post->created_at }}</span>
        </div>
        <div class="detsail_post_title">{{ $post->post_title }}</div>
        <div class="mt-3 detsail_post">{{ $post->post }}</div>
      </div>
      <div class="p-3">
        <div class="comment_container">
          <span class="">コメント</span>
          @foreach($post->postComments as $comment)
          <div class="comment_area border-top">
            <p>
              <span>{{ $comment->commentUser($comment->user_id)->over_name }}</span>
              <span>{{ $comment->commentUser($comment->user_id)->under_name }}</span>さん
            </p>
            <p>{{ $comment->comment }}</p>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  <div class="w-50 p-3">
    <div class="comment_container border m-5">
      <div class="comment_area p-3">
      @if($errors->first('comment'))
      <span class="error_message">{{ $errors->first('comment') }}</span>
      @endif
        <p class="m-0">コメントする</p>
        <form action="{{ route('comment.create') }}" method="post" id="commentRequest">{{ csrf_field() }}
        @csrf
        <textarea class="w-100" name="comment" form="commentRequest">{{ old('comment') }}</textarea>
        <input type="hidden" name="post_id" form="commentRequest" value="{{ $post->id }}">
        <input type="submit" class="btn btn-primary" form="commentRequest" value="投稿">
    <!-- @if($errors->any())
         <div class="alert alert-danger">
           <ul>
               @foreach($errors->all() as $error)
             <li>{{ $error }}</li>
               @endforeach
            </ul>
         </div>
         @endif -->
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal js-modal">
  <div class="modal__bg js-modal-close"></div>
  <div class="modal__content">
    <form action="{{ route('post.edit') }}" method="post">
    @csrf
      <div class="w-100">
        <div class="modal-inner-title w-50 m-auto">
        <input type="hidden" class="edit-modal-hidden" name="post_id" value="{{ $post->id }}">
          <input type="text" name="post_title" placeholder="タイトル" class="w-100">
        </div>
        <div class="modal-inner-body w-50 m-auto pt-3 pb-3">
          <textarea placeholder="投稿内容" name="post_body" class="w-100"></textarea>
        </div>
        <div class="w-50 m-auto edit-modal-btn d-flex">
          <a class="js-modal-close btn btn-danger d-inline-block" href="">閉じる</a>
          <input type="hidden" class="edit-modal-hidden" name="post_id" value="">
          <input type="submit" class="btn btn-primary d-block" value="編集">
        </div>
      </div>
      {{ csrf_field() }}
    </form>
  </div>
</div>
@endsection