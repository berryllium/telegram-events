@extends('base')
@section('title', __('webapp.bots.edit'))
@section('content')
    <form method="post" action="{{ route('bot.update', [$bot]) }}">
        @csrf
        @method('put')
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('webapp.title') }}</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $bot->name }}">
            @error('name')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="code" class="form-label">{{ __('webapp.code') }}</label>
            <input type="text" class="form-control" id="code" name="code" value="{{ $bot->code }}">
            @error('code')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="api_token" class="form-label">{{ __('webapp.api_token') }}</label>
            <input type="text" class="form-control" id="api_token" name="api_token" value="{{ $bot->api_token }}">
            @error('api_token')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="moderation_group" class="form-label">{{ __('webapp.moderation_group') }}</label>
            <input type="text" class="form-control" id="moderation_group" name="moderation_group" value="{{ $bot->moderation_group }}">
            @error('moderation_group')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="form_id" class="form-label">{{ __('webapp.webapp_form') }}</label>
            <select class="form-select" id="form_id" name="form_id">
                <option value=""></option>
                @foreach(\App\Models\Form::all() as $form)
                    <option value="{{ $form->id }}" {{ $form->id == $bot->form_id ? 'selected' : ''}}>{{ $form->name }}</option>
                @endforeach
            </select>
            @error('form_id')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">{{ __('webapp.description') }}</label>
            <textarea class="form-control" id="description" name="description" rows="5">{{ $bot->description }}</textarea>
            @error('description')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">{{ __('webapp.update') }}</button>
    </form>
@endsection