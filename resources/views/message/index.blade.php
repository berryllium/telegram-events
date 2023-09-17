@extends('base')
@section('title', 'Сообщения')
@section('content')

    @include('component.table', [
        'entities' => ['name' => 'message', 'value' => $messages],
        'headers' => ['id', 'Автор', 'Текст', 'Время добавления'],
        'fields' => ['id', 'author', 'text', 'created_at']
    ])

@endsection