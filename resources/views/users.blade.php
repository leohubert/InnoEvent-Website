@extends('layouts.app')

@section('content')
    @foreach($users as $user)
        Name : {{ $user->name }}<br>
        Email : {{ $user->email }}<br><br>
    @endforeach
@endsection