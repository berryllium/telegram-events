@extends('base')
@section('title', 'Список ТГ-каналов')
@section('content')

    <a href="{{ route('channel.create') }}" class="btn btn-primary mb-4">Добавить канал</a>

    @include('component.table', [
        'entities' => ['name' => 'channel', 'value' => $channels],
        'headers' => ['Название', 'Описание', 'Телеграм ID'],
        'fields' => ['name', 'description', 'tg_id']
    ])

@endsection