@extends('base')
@section('title', __('webapp.dictionaries.list'))
@section('content')

    <a href="{{ route('dictionary.create') }}" class="btn btn-primary mb-4">{{ __('webapp.add') }}</a>

    @include('component.table', [
        'entities' => ['name' => 'dictionary', 'value' => $dictionaries],
        'headers' => [__('webapp.name'), __('webapp.description')],
        'fields' => ['name', 'description']
    ])
@endsection