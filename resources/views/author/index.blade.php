@extends('base')
@section('title', 'Список Авторов постов')
@section('content')

    <a href="{{ route('author.create') }}" class="btn btn-primary mb-4">Добавить автора</a>

    @include('component.table', [
        'entities' => ['name' => 'author', 'value' => $authors],
        'headers' => ['Имя', 'Описание', 'Телеграм ID'],
        'fields' => ['name', 'description', 'tg_id']
    ])

@endsection