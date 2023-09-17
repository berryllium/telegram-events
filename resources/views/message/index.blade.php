@extends('base')
@section('title', 'Сообщения')
@section('content')

    <a href="{{ route('channel.create') }}" class="btn btn-primary mb-4">Добавить канал</a>

    @include('component.table', [
        'entities' => ['name' => 'message', 'value' => $messages],
        'headers' => ['id', 'Автор', 'Текст', 'Время добавления'],
        'fields' => ['id', 'author', 'text', 'created_at']
    ])

@endsection