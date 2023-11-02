@extends('base')
@section('title', __('webapp.forms.add') )
@section('content')
    <form method="post" action="{{ route('form.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('webapp.title') }}</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
            @error('name')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="default_price_type" class="form-label">{{ __('webapp.default_price_type') }}</label>
            <select class="form-select" id="default_price_type" name="default_price_type">
                @foreach(\App\Models\Form::$price_types as $type)
                    <option value="{{ $type }}" {{ $type === old('default_price_type') ? 'selected' : '' }}>{{ __("webapp.price_$type") }}</option>
                @endforeach
            </select>
            @error('default_price_type')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">{{ __('webapp.description') }}</label>
            <textarea class="form-control" id="description" name="description" rows="5">{{ old('description') }}</textarea>
            @error('description')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="template" class="form-label">{{ __('webapp.forms.template') }}</label>
            <textarea class="form-control" id="template" name="template" rows="5">{{ old('template') }}</textarea>
            @error('template')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">{{ __('webapp.add') }}</button>
    </form>
@endsection