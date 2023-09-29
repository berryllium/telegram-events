@extends('base')
@section('title', __('webapp.authors.list'))
@section('content')

    <a href="{{ route('author.create') }}" class="btn btn-primary mb-4">{{ __('webapp.add') }}</a>

    @include('component.table', [
        'entities' => ['name' => 'author', 'value' => $authors],
        'headers' => [__('webapp.name'), __('webapp.description'), __('webapp.tg_id')],
        'fields' => ['name', 'description', 'tg_id']
    ])

@endsection