@extends('base')
@section('title', __('webapp.dictionary.edit'))
@section('content')
    <form method="post" action="{{ route('dictionary.update', [$dictionary]) }}">
        <input type="hidden" name="tg_channel" value="1">
        @csrf
        @method('put')
        <div class="mb-3">
            <label for="name" class="form-label">Название формы</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $dictionary->name }}">
            @error('name')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <textarea class="form-control" id="description" name="description" rows="5">{{ $dictionary->description }}</textarea>
            @error('description')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Обновить</button>
    </form>

{{--    <h3 class="mt-4">{{ 'webapp.dictionary.values' }}</h3>--}}
{{--    @include('dictionary.values', ['dictionary' => $dictionary])--}}
{{--    <a href="{{ route('dictionary_value.create', ['dictionary' => $dictionary]) }}" class="btn btn-primary">{{ __('webapp.add') }}</a>--}}
@endsection