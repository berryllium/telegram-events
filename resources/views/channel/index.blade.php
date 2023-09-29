@extends('base')
@section('title', __('webapp.channels'))
@section('content')

    <a href="{{ route('channel.create') }}" class="btn btn-primary mb-4">{{ __('webapp.add') }}</a>

    @include('component.table', [
        'entities' => ['name' => 'channel', 'value' => $channels],
        'headers' => [__('webapp.name'), __('webapp.description'), __('webapp.tg_id')],
        'fields' => ['name', 'description', 'tg_id']
    ])

@endsection