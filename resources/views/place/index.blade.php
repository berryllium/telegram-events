@extends('base')
@section('title', __('webapp.places.list'))
@section('content')

    <a href="{{ route('place.create') }}" class="btn btn-primary mb-4">{{ __('webapp.add') }}</a>

    @include('place.component.filter')
    @if(request('search') && !$places->count())
        <div class="text-center">По Вашему запросу ничего не найдено</div>
    @else
        @include('component.table', [
            'entities' => ['name' => 'place', 'value' => $places],
            'headers' => [__('webapp.title'), __('webapp.address'),  __('webapp.description')],
            'fields' => ['name', 'address', 'description']
        ])
    @endif
@endsection