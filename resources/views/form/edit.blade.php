@extends('base')
@section('title', __('webapp.forms.edit', ['form' => $form->name]))
@section('content')
    <p class="text-center">
        <strong>{{ __('webapp.attention') }}</strong>
        {!! __('webapp.forms.notice') !!}
    </p>
    <form method="post" action="{{ route('form.update', [$form]) }}">
        <input type="hidden" name="tg_channel" value="1">
        @csrf
        @method('put')
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('webapp.title') }}</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $form->name }}">
            @error('name')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="default_price_type" class="form-label">{{ __('webapp.default_price_type') }}</label>
            <select class="form-select" id="default_price_type" name="default_price_type">
                @foreach(\App\Models\Form::$price_types as $type)
                    <option value="{{ $type }}" {{ $type === $form->default_price_type ? 'selected' : '' }}>{{ __("webapp.price_$type") }}</option>
                @endforeach
            </select>
            @error('default_price_type')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">{{ __('webapp.description') }}</label>
            <textarea class="form-control" id="description" name="description" rows="5">{{ $form->description }}</textarea>
            @error('description')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="template" class="form-label">{{ __('webapp.forms.template') }}</label>
            <textarea class="form-control" id="template" name="template" rows="20">{{ $form->template }}</textarea>
            @error('template')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">{{ __('webapp.update') }}</button>
    </form>

    <h3 class="mt-4">{{ __('webapp.forms.fields') }}</h3>
    @include('form.field', ['form' => $form])
    <a href="{{ route('field.create', ['form' => $form]) }}" class="btn btn-primary">{{ __('webapp.forms.add_field') }}</a>
@endsection