@extends('base')
@section('title', __('webapp.dictionary.list'))
@section('content')

    <a href="{{ route('dictionary.create') }}" class="btn btn-primary mb-4">{{ __('webapp.add') }}</a>

    @include('component.table', [
        'entities' => ['name' => 'dictionary', 'value' => $dictionaries],
        'headers' => ['Название', 'Описание'],
        'fields' => ['name', 'description']
    ])
@endsection