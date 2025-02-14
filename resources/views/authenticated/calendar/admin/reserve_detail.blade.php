@extends('layouts.sidebar')

@section('content')
<div class="vh-100 d-flex" style="align-items:center; justify-content:center;">
@foreach($reservePersons as $reservePerson)
  <div class="w-50 m-auto h-75">
    <p>
      <span>{{ $reservePerson->formatted_reserve_date}}</span><span class="ml-3">{{ $reservePerson->setting_part }}部</span></p>
    <div class="h-75 border">
      <table class="">
      <thead>
        <tr class="text-center">
          <th class="w-25">ID</th>
          <th class="w-25">名前</th>
          <th class="w-25">場所</th>
        </tr>
        </thead>
          <tbody>
        @foreach($reservePerson->users as $user)
        <tr class="text-center">
          <td class="w-25">{{ $user->id }}</td>
          <td class="w-25">{{ $user->over_name }}{{ $user->under_name }}</td>
          <td class="w-25">リモート</td>
        </tr>
      @endforeach
     </tbody>
      </table>
    </div>
  </div>
  @endforeach
</div>
@endsection