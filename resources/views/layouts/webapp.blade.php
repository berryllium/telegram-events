<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="/css/webapp.css">
    @if(Route::current()->getName() == 'webapp')
        <script src="https://telegram.org/js/telegram-web-app.js"></script>
    @endif
    <script src="/js/webapp.js?ver={{ rand(1, 100) }}"></script>
</head>
<body>
<div id="webapp" class="container">
    @yield('content')
</div>
</body>
</html>
