@extends('base')
@section('title', __('webapp.forms.list'))
@section('content')

    <a href="{{ route('form.create') }}" class="btn btn-primary mb-4">{{ __('webapp.add') }}</a>

    @include('component.table', [
        'entities' => ['name' => 'form', 'value' => $forms],
        'headers' => [__('webapp.name'), __('webapp.description')],
        'fields' => ['name', 'description']
    ])
@endsection