@php
    $user = auth()->user();
@endphp

@extends('layouts.app', ['user' => $user])

@section('content')
<home :user="{{ json_encode($user) }}"></home>
<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
            <home :user="{{ json_encode($user) }}"></home>
        </div>
    </div>
</div> -->
@endsection
