@extends('base')
@section('title', __('webapp.places.list'))
@section('content')

    <a href="{{ route('place.create') }}" class="btn btn-primary mb-4">{{ __('webapp.add') }}</a>

    @include('component.table', [
        'entities' => ['name' => 'place', 'value' => $places],
        'headers' => [__('webapp.title'), __('webapp.address'),  __('webapp.description')],
        'fields' => ['name', 'address', 'description']
    ])
@endsection