@extends('base')
@section('title', __('webapp.forms.add'))
@section('content')
    <form method="post" action="{{ route('place.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('webapp.title') }}</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
            @error('name')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">{{ __('webapp.address') }}</label>
            <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}">
            @error('address')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="form" class="form-label">{{ __('webapp.form') }}</label>
            <select class="form-select" id="form" name="form">
                <option value=""></option>
                @foreach(\App\Models\Form::all() as $form)
                    <option value="{{ $form->id }}" {{ $form->id == old('form') ? 'selected' : ''}}>{{ $form->name }}</option>
                @endforeach
            </select>
            @error('form')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="channels" class="form-label">{{ __('webapp.channels') }}</label>
            <select class="form-select" id="channels" name="channels[]" multiple>
                @foreach(\App\Models\TelegramChannel::all() as $channel)
                    <option value="{{ $channel->id }}" {{ old('channels') && in_array($channel->id, old('channels')) ? 'selected' : ''}}>
                        {{ $channel->name }}
                    </option>
                @endforeach
            </select>
            @error('form')
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
        <button type="submit" class="btn btn-primary">{{ __('webapp.add') }}</button>
    </form>
@endsection