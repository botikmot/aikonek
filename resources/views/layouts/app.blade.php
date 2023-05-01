<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/chatify/font.awesome.min.js') }}"></script>
    <script src="{{ asset('js/chatify/autosize.js') }}"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/chatify/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/chatify/dark.mode.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/chatify/light.mode.css') }}" rel="stylesheet" /> 
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <v-app>
            @if (Auth::check())
                @php
                    $user = auth()->user();
                @endphp
                <navigation :user="{{ json_encode($user) }}"></navigation>
            @endif

            @yield('content')
            <!-- <main class="py-4">
                @yield('content')
            </main> -->
        </v-app>
    </div>
    <script src="https://js.pusher.com/7.0.3/pusher.min.js"></script>
    <script >
  // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher("{{ config('chatify.pusher.key') }}", {
        encrypted: true,
        cluster: "{{ config('chatify.pusher.options.cluster') }}",
        authEndpoint: '{{route("pusher.auth")}}',
        auth: {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }
    });

        // Bellow are all the methods/variables that using php to assign globally.
        const allowedImages = {!! json_encode(config('chatify.attachments.allowed_images')) !!} || [];
        const allowedFiles = {!! json_encode(config('chatify.attachments.allowed_files')) !!} || [];
        const getAllowedExtensions = [...allowedImages, ...allowedFiles];
        const getMaxUploadSize = {{ Chatify::getMaxUploadSize() }};
    </script>
    <script src="{{ asset('js/chatify/code.js') }}"></script>
</body>
</html>
