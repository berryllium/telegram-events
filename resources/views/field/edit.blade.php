@extends('base')
@section('title', __('webapp.fields.edit'))
@section('content')
    @php /** @var \App\Models\Field $field */ @endphp
    <form method="post" action="{{ route('field.update', ['form' => $form, 'field' => $field]) }}">
        @csrf
        @method('put')
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('webapp.title') }}</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $field->name }}">
            @error('name')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="code" class="form-label">{{ __('webapp.code') }}</label>
            <input type="text" class="form-control" id="code" name="code" value="{{ $field->code }}"
                    {{ $field->code == 'place' ? 'disabled' : '' }}>
            @error('code')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">{{ __('webapp.type') }}</label>
            <select class="form-select" id="type" disabled>
                <option value="">{{ $field->typeName }}</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="sort" class="form-label">{{ __('webapp.sort') }}</label>
            <input type="number" class="form-control" id="sort" name="sort" value="{{ $field->sort }}">
            @error('sort')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        @if($field->canHaveDictionary)
            <div class="mb-3">
                <label for="dictionary_id" class="form-label">{{ __('webapp.dictionaries.dictionary') }}</label>
                <select class="form-select" id="dictionary_id" name="dictionary_id">
                    <option value=""></option>
                    @foreach($dictionaries as $dictionary)
                        <option value="{{ $dictionary->id }}" {{ $field->dictionary_id == $dictionary->id ? 'selected' : '' }}>
                            {{ $dictionary->name }}
                        </option>
                    @endforeach
                </select>
                @error('description')
                <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
        @endif
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="required" name="required" value="1" {{ $field->required ? 'checked' : ''}}>
            <label for="required" class="form-check-label">{{ __('webapp.required') }}</label>
        </div>
        <button type="submit" class="btn btn-primary">{{ __('webapp.update') }}</button>
    </form>
@endsection