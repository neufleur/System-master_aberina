@extends('layouts.sidebar')

@section('content')
<!-- <div class="w-75 m-auto">
  <div class="w-100"> -->
    <!-- スクール予約確認画面 -->
  <div class="vh-100 pt-5" style="background:#ECF1F6;">
  <div class="border w-75 m-auto pt-5 pb-5" style="border-radius:5px; background:#FFF;">
    <div class="w-75 m-auto border" style="border-radius:5px;">
    <p class="text-center">{{ $calendar->getTitle() }}</p>
    <p>{!! $calendar->render() !!}</p>
    @if(session('reserved_dates'))
    <ul> @foreach(session('reserved_dates') as $date)
       <li>{{ $date }}</li>
         @endforeach
    </ul>
    @endif

  </div>
</div>
  </div>
@endsection