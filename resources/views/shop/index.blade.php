@extends('base')
@section('title', 'Список веб-форм')
@section('content')

    <a href="{{ route('form.create') }}" class="btn btn-primary mb-4">Создать новую форму</a>

    @include('component.table', [
        'entities' => ['name' => 'shop', 'value' => $shops],
        'headers' => ['Название', 'Описание'],
        'fields' => ['name', 'description']
    ])
@endsection