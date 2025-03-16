@extends('layouts.sidebar')
@section('content')
<div class="w-100 d-flex" style="align-items:center; justify-content:center; min-height: 100vh; background-color:#ECF1F6;">
<div class="w-75 mt-4">
    <div class="m-3 detail_container" style="box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
      <div class="p-3">
        <div class="calendar-container">
        <p class="text-s-center">{{ $calendar->getTitle() }}</p>
        <div class="calendar-wrapper">
            {!! $calendar->render() !!}
          </div>
    <div class="adjust-table-btn m-auto text-right ">
      <input type="submit" class="btn btn-primary" value="登録" form="reserveSetting" onclick="return confirm('登録してよろしいですか？')">
    </div>
  </div>
  </div>
</div>
</div>
@endsection