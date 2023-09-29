@extends('base')
@section('title', __('webapp.dictionaries.edit'))
@section('content')
    <form method="post" action="{{ route('dictionary.update', [$dictionary]) }}">
        <input type="hidden" name="tg_channel" value="1">
        @csrf
        @method('put')
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('webapp.title') }}</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $dictionary->name }}">
            @error('name')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">{{ __('webapp.description') }}</label>
            <textarea class="form-control" id="description" name="description" rows="5">{{ $dictionary->description }}</textarea>
            @error('description')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">{{ __('webapp.dictionaries.values') }}</label>
            @foreach($dictionary->dictionary_values as $item)
                <div class="form-group mb-2">
                    <label class="d-block">
                        <input
                                type="text"
                                class="form-control"
                                id="dictionary_value-{{ $item->id }}"
                                name="dictionary_values[{{ $item->id }}]"
                                value="{{ $item->value }}"
                        >
                    </label>
                </div>
            @endforeach
            <div class="form-group mb-2">
                <label class="d-block">
                    <input type="text" class="form-control" name="new_values[]">
                </label>
            </div>
            <div class="btn btn-primary" data-role="copy-block">+</div>
        </div>
        <button type="submit" class="btn btn-primary">{{ __('webapp.update') }}</button>
    </form>
@endsection