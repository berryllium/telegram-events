@extends('base')
@section('title', __('webapp.fields.add'))
@section('content')
    <form method="post" action="{{ route('field.store', ['form' => $form]) }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('webapp.title') }}</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
            @error('name')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="code" class="form-label">{{ __('webapp.code') }}</label>
            <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}">
            @error('code')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">{{ __('webapp.type') }}</label>
            <select class="form-select" id="type" name="type" data-role="toggle-block" data-block="dictionary-block">
                @foreach(\App\Models\Field::$types as $id => $type)
                    <option value="{{ $id }}">{{ $type }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="sort" class="form-label">{{ __('webapp.sort') }}</label>
            <input type="number" class="form-control" id="sort" name="sort" value="{{ old('sort') ?: 100 }}">
            @error('sort')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3" id="dictionary-block" style="display: none;">
            <label for="dictionary_id" class="form-label">{{ __('webapp.dictionaries.dictionary') }}</label>
            <select class="form-select" id="dictionary_id" name="dictionary_id">
                <option value=""></option>
                @foreach($dictionaries as $dictionary)
                    <option value="{{ $dictionary->id }}" {{ old('dictionary_id') == $dictionary->id ? 'selected' : '' }}>
                        {{ $dictionary->name }}
                    </option>
                @endforeach
            </select>
            @error('description')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="required" name="required" value="1" {{ old('required') ? 'checked' : ''}}>
            <label for="required" class="form-check-label">{{ __('webapp.required') }}</label>
        </div>
        <button type="submit" class="btn btn-primary">{{ __('webapp.add') }}</button>
    </form>
@endsection
<script src="{{ asset('asset/js/pages/field.create.js') }}"></script>