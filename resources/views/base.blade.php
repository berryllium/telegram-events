<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="d-flex flex-column justify-content-between h-100">
<header class=" bg-light">
    <div class="container-fluid">
        <nav class="navbar navbar-light bg-light justify-content-between">
            <a class="navbar-brand">{{ auth()->user()->name ?? 'User' }}</a>
            <a class="link-dark" href="{{ route('form.index') }}"  type="submit">Выйти</a>
        </nav>
    </div>
</header>
<div class="container-fluid mt-5 flex-grow-1">
    <h1 class="text-center mb-4" >@yield('title')</h1>
    <div class="row">
        <div class="col-12 col-lg-2 mb-4">
            <div class="list-group">
                <a href="{{ route('form.index') }}" class="list-group-item list-group-item-action active">Формы</a>
                <a href="{{ route('channel.index') }}" class="list-group-item list-group-item-action">ТГ Каналы</a>
                <a href="#" class="list-group-item list-group-item-action">Поля</a>
                <a href="#" class="list-group-item list-group-item-action">Боты</a>
                <a href="#" class="list-group-item list-group-item-action">Авторы</a>
                <a href="#" class="list-group-item list-group-item-action">Организации</a>
            </div>
        </div>
        <div class="col-12 col-lg-10">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">{{ $message }}</div>
            @endif
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>