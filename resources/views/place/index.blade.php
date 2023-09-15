@extends('base')
@section('title', 'Список мест')
@section('content')

    <a href="{{ route('place.create') }}" class="btn btn-primary mb-4">Создать новое место</a>

    @include('component.table', [
        'entities' => ['name' => 'place', 'value' => $places],
        'headers' => ['Название', 'Адрес', 'WebApp Форма', 'Описание'],
        'fields' => ['name', 'address', 'form', 'description']
    ])
@endsection