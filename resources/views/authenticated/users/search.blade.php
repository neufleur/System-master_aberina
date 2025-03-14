@extends('layouts.sidebar')

@section('content')
<div class="search_content w-100 d-flex">
  <div class="reserve_users_area">
    @foreach($users as $user)
    <div class="border one_person">
      <div>
        <span class="myself">ID : </span><span>{{ $user->id }}</span>
      </div>
      <div><span class="myself">名前 : </span>
        <a href="{{ route('user.profile', ['id' => $user->id]) }}">
          <span>{{ $user->over_name }}</span>
          <span>{{ $user->under_name }}</span>
        </a>
      </div>
      <div>
        <span class="myself">カナ : </span>
        <span>({{ $user->over_name_kana }}</span>
        <span>{{ $user->under_name_kana }})</span>
      </div>
      <div>
        @if($user->sex == 1)
        <span class="myself">性別 : </span><span>男</span>
        @elseif($user->sex == 2)
        <span class="myself">性別 : </span><span>女</span>
        @else
        <span class="myself">性別 : </span><span>その他</span>
        @endif
      </div>
      <div>
        <span class="myself">生年月日 : </span><span>{{ $user->birth_day }}</span>
      </div>
      <div>
        @if($user->role == 1)
        <span class="myself">権限 : </span><span>教師(国語)</span>
        @elseif($user->role == 2)
        <span class="myself">権限 : </span><span>教師(数学)</span>
        @elseif($user->role == 3)
        <span class="myself">権限 : </span><span>講師(英語)</span>
        @else
        <span class="myself">権限 : </span><span>生徒</span>
        @endif
      </div>
      <div>
        @if($user->role == 4)
        <span class="myself">選択科目 </span>
        <span>:{{ $user->subjects->pluck('subject')->implode(', ') }}</span>
        <!-- subjectsリレーションからsubjectカラムを呼び出す　pluckは必要なカラムのデータだけ取り出せるメソッド(国語　数学などの配列を返す)　implode配列をカンマ区切りの文字列に変換する-->
        @endif
      </div>
    </div>
    @endforeach
  </div>
  <div class="search_area w-25">
    <div class="">
      <div>
        <p class="just_search">検索</p>
        <input type="text" class="free_word" name="keyword" placeholder="キーワードを検索" form="userSearchRequest">
      </div>
      <div class="category_name_id">
        <lavel>カテゴリ</lavel>
        <select form="userSearchRequest" name="category" class="name_id">
          <option value="name">名前</option>
          <option value="id">社員ID</option>
        </select>
      </div>
      <div class="up_down">
        <label>並び替え</label>
        <select name="updown" form="userSearchRequest"  class="up">
          <option value="ASC">昇順</option>
          <option value="DESC">降順</option>
        </select>
      </div>
      <div class="">
        <p class="m-0 search_conditions"><span>検索条件の追加</span><span class="nav-btn" style="height: 25px;"></span></p>
        <div class="search_conditions_inner">
          <div class="sex123">
            <label>性別</label>
            <div class="s-group">
            <span class="sex">男</span><input type="radio" name="sex" value="1" form="userSearchRequest">
            <span class="sex">女</span><input type="radio" name="sex" value="2" form="userSearchRequest">
            <span class="sex">その他</span><input type="radio" name="sex" value="3" form="userSearchRequest">
            </div>
          </div>
          <div class="role1234">
            <label>権限</label>
            <select name="role" form="userSearchRequest" class="engineer">
              <option selected disabled>----</option>
              <option value="1">教師(国語)</option>
              <option value="2">教師(数学)</option>
              <option value="3">教師(英語)</option>
              <option value="4" class="">生徒</option>
            </select>
          </div>
          <div class="selected_engineer">
            <label>選択科目</label>
              <!--subject 単数で表示  -->
              <!-- <option selected disabled>----</option> -->
              <div class="selected">
              <span>国語</span><input type="checkbox" name="subjects[]" value="1" form="userSearchRequest">
              <span>数学</span><input type="checkbox" name="subjects[]" value="2" form="userSearchRequest">
              <span>英語</span><input type="checkbox" name="subjects[]" value="3" form="userSearchRequest">
            </select>
            </div>
          </div>
        </div>
      </div>
      <div>
      <input type="submit" name="search_btn" value="検索" form="userSearchRequest" class="search_btn">
      </div>
      <div>
      <input type="reset" value="リセット" form="userSearchRequest" class="reset_btn">
      </div>
    </div>
    <form action="{{ route('user.show') }}" method="get" id="userSearchRequest"></form>
  </div>
</div>
@endsection
