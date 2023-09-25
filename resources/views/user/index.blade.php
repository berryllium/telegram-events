@extends('base')
@section('title', __('webapp.users.list'))
@section('content')

    <a href="{{ route('user.create') }}" class="btn btn-primary mb-4">{{ __('webapp.add') }}</a>

    @include('user.component.filter')
    @include('component.table', [
        'entities' => ['name' => 'user', 'value' => $users],
        'headers' => [__('webapp.name'), 'Email'],
        'fields' => ['name', 'email']
    ])

@endsection