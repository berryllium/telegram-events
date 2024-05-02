@extends('base')
@section('title', __('webapp.channels'))
@section('content')

    <a href="{{ route('channel.create') }}" class="btn btn-primary mb-4">{{ __('webapp.add') }}</a>

    @include('channel.component.filter')
    @if(request('search') && !$channels->count())
        <div class="text-center">По Вашему запросу ничего не найдено</div>
    @else
        @include('component.table', [
            'entities' => ['name' => 'channel', 'value' => $channels],
            'headers' => [__('webapp.name'), __('webapp.description'), __('webapp.channel_id')],
            'fields' => ['name', 'description', 'tg_id']
        ])
    @endif

@endsection