<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>AtlasBulletinBoard</title>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300&family=Oswald:wght@200&display=swap" rel="stylesheet">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body class="all_content">
  <div class="d-flex">
    <div class="sidebar">
      @section('sidebar')
      <p style="margin-top:10px;"><a href="{{ route('top.show') }}"style="display: flex; align-items: center;"><img src="{{ asset('image/home.png') }}" width="35px" height="35px" style="margin-left: 2px;">マイページ</a></p>
      <p><a href="/logout"><img src="{{ asset('image/logout.png') }}" width="30px" height="30px" style="margin-left: 5px;">ログアウト</a></p>
      <p><a href="{{ route('calendar.general.show',['user_id' => Auth::id()]) }}"><img src="{{ asset('image/booking.png') }}" width="25px" height="25px" style="margin-left: 5px;">スクール予約</a></p>
<!-- ⓶ログインユーザーが講師である時に表示させる文章をif文で作成 -->
      @if (Auth::check() && Auth::user()->isTeacher())
      <p><a href="{{ route('calendar.admin.show',['user_id' => Auth::id()]) }}"><img src="{{ asset('image/booked.png') }}" width="25px" height="25px" style="margin-left: 5px;">スクール予約確認</a></p>
      <p><a href="{{ route('calendar.admin.setting',['user_id' => Auth::id()]) }}"><img src="{{ asset('image/register.png') }}" width="25px" height="25px" style="margin-left: 5px;">スクール枠登録</a></p>
      @endif
      <p><a href="{{ route('post.show') }}"><img src="{{ asset('image/board.png') }}" width="27px" height="27px" style="margin-left: 5px;">掲示板</a></p>
      <p><a href="{{ route('user.show') }}"><img src="{{ asset('image/search.png') }}" width="30px" height="30px" style="margin-left: 5px;" >ユーザー検索</a></p>
      @show
    </div>
    <div class="main-container">
      @yield('content')
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="{{ asset('js/register.js') }}" rel="stylesheet"></script>
  <script src="{{ asset('js/bulletin.js') }}" rel="stylesheet"></script>
  <script src="{{ asset('js/user_search.js') }}" rel="stylesheet"></script>
  <script src="{{ asset('js/calendar.js') }}" rel="stylesheet"></script>
</body>
</html>