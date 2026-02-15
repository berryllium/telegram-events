@extends('base')
@section('title', __('webapp.bots.add'))
@section('content')
    <p class="text-center">
        <strong>Внимание!</strong>
        при добавлении бота происходит привязка его вебхука к данному сайту и отвязка от остальных!
    </p>
    <form method="post" action="{{ route('bot.store') }}">
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
            <input type="text" class="form-control" id="code" name="code" value="{{ uniqid() }}">
            @error('code')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="slug" class="form-label">{{ __('webapp.slug') }}</label>
            <input type="text" class="form-control" id="slug" name="slug" value="">
            @error('slug')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="api_token" class="form-label">{{ __('webapp.api_token') }}</label>
            <input type="text" class="form-control" id="api_token" name="api_token" value="{{ old('api_token') }}">
            @error('api_token')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="moderation_group" class="form-label">{{ __('webapp.moderation_group') }}</label>
            <input type="text" class="form-control" id="moderation_group" name="moderation_group" value="{{ old('moderation_group') }}">
            @error('moderation_group')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="comments_channel_id" class="form-label">{{ __('webapp.comments_channel_id') }}</label>
            <input type="text" class="form-control" id="comments_channel_id" name="comments_channel_id" value="{{ old('comments_channel_id') }}">
            @error('comments_channel_id')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="form_id" class="form-label">{{ __('webapp.webapp_form') }}</label>
            <select class="form-select" id="form_id" name="form_id">
                <option value=""></option>
                @foreach($forms as $form)
                    <option value="{{ $form->id }}" {{ $form->id == old('form') ? 'selected' : ''}}>{{ $form->name }}</option>
                @endforeach
            </select>
            @error('form_id')
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
            <label for="links" class="form-label">{{ __('webapp.project_links') }}</label>
            <textarea class="form-control" id="links" name="links" rows="5">{{ old('links') }}</textarea>
            @error('links')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">{{ __('webapp.create') }}</button>
    </form>
@endsection