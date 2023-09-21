@extends('base')
@section('title', __('webapp.bots.list'))
@section('content')

    <a href="{{ route('bot.create') }}" class="btn btn-primary mb-4">{{ __('webapp.add') }}</a>

    @include('component.table', [
        'entities' => ['name' => 'bot', 'value' => $bots],
        'headers' => [__('webapp.title'), __('webapp.form'), __('webapp.description')],
        'fields' => ['name', 'form', 'description']
    ])

@endsection