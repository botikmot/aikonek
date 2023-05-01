@php
    $user = auth()->user();
@endphp

@extends('layouts.app', ['user' => $user])

@section('content')
<home :user="{{ json_encode($user) }}"></home>
@endsection
