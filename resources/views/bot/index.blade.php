@extends('base')
@section('title', 'Список ТГ-ботов')
@section('content')

    <a href="{{ route('bot.create') }}" class="btn btn-primary mb-4">Добавить бот</a>

    @include('component.table', [
        'entities' => ['name' => 'bot', 'value' => $bots],
        'headers' => ['Название', 'Описание'],
        'fields' => ['name', 'description']
    ])

@endsection