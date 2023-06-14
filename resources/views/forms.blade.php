@php
    $user = auth()->user();
@endphp

@extends('layouts.app', ['user' => $user])

@section('content')
<forms :user="{{ json_encode($user) }}"></forms>
@endsection
