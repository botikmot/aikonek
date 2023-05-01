@php
    $user = auth()->user();
@endphp

@extends('layouts.app', ['user' => $user])

@section('content')
<myprofile :user="{{ json_encode($user) }}"></myprofile>
@endsection
