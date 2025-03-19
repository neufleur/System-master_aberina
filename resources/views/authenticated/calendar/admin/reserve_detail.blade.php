@extends('layouts.sidebar')

@section('content')
<div class="vh-100 d-flex" style="align-items:center; justify-content:start; padding-left: 10rem;">
@foreach($reservePersons as $reservePerson)
  <div class="w-75 h-75">
    <p style="font-size:20px;">
      <span>{{ $reservePerson->formatted_reserve_date}}</span><span class="ml-3">{{ $reservePerson->setting_part }}部</span></p>
    <div class="h-75">
    <table class="reservePersons border w-100" style="border-collapse: separate; border-spacing: 0; overflow: hidden;">
      <thead>
        <tr class="text-center-title">
          <th class="w-25" style="padding:4px;">ID</th>
          <th class="w-25">名前</th>
          <th class="w-25">場所</th>
        </tr>
        </thead>
          <tbody>
        @foreach($reservePerson->users as $user)
        <tr class="text-center-detail">
          <td class="w-25" style="padding:10px; text-align:center;">{{ $user->id }}</td>
          <td class="w-25" style="text-align:center;">{{ $user->over_name }}{{ $user->under_name }}</td>
          <td class="w-25" style="text-align:center;">リモート</td>
        </tr>
      @endforeach
     </tbody>
      </table>
    </div>
  </div>
  @endforeach
</div>
@endsection