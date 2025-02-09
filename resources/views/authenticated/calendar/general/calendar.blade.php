@extends('layouts.sidebar')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/register.js') }}"></script>
<div class="vh-100 pt-5" style="background:#ECF1F6;">
  <div class="border w-75 m-auto pt-5 pb-5" style="border-radius:5px; background:#FFF;">
    <div class="w-75 m-auto border" style="border-radius:5px;">

      <p class="text-center">{{ $calendar->getTitle() }}</p>
      <div class="">
      {!! $calendar->render() !!}
    </div>
    <div class="text-right w-75 m-auto">
      <input type="submit" class="btn btn-primary" value="予約する" form="reserveParts"id="reserve-btn">
    </div>
    <div id="reserve-modal" class="modal" style="display:none;">
    <div class="modal-content">
        <h2 id="modal-date"></h2>
        <p id="modal-time"></p>
        <p>上記の予約をキャンセルしてもよろしいですか？</p>
        <button class="btn btn-primary js-modal-close">閉じる</button>
        <button type="submit" class="btn btn-danger">キャンセル</button>
    </div>
</div>
  </div>
</div>
@endsection