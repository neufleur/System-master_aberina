@extends('layouts.sidebar')

@section('content')
<div class="vh-100 pt-5" style="background:#ECF1F6;">
  <div class="border w-75 m-auto pt-5 pb-5" style="border-radius:10px; background:#FFF; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
    <div class="w-75 m-auto" style="border-radius:5px;">

      <p class="text-center">{{ $calendar->getTitle() }}</p>
      <div class="">
      {!! $calendar->render() !!}
    </div>
    <div class="text-right w-75 m-auto">
      <input type="submit" class="btn btn-primary" value="予約する" form="reserveParts"id="reserve-btn">
    </div>
    <div id="reserve-modal" class="modal" style="display:none;">
    <div class="modal__bg js-modal-close"></div>
    <div class="modal__content" style="padding-left:300px;">
        <h2 id="modal-date" style="font-size: 16px; margin-bottom: 15px;"></h2>
        <p id="modal-part"></p>
        <p>上記の予約をキャンセルしてもよろしいですか？</p>
        <button class="btn btn-primary js-modal-close" style="padding: 4px 20px 4px 20px;">閉じる</button>
        <button type="button" class="btn btn-danger js-delete-reserve" style="padding: 4px 20px 4px 20px;margin-left:160px;">キャンセル</button>
    </div>
</div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/register.js') }}"></script>
@endsection