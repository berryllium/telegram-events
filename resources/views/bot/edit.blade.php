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
                @foreach($forms as $form)
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
        @if(config('app.debug'))
        <div class="mb-3">
            <div>Info</div>
            <div>{{ $info->pendingUpdateCount }}</div>
        </div>
        @endif
        <button type="submit" class="btn btn-primary">{{ __('webapp.update') }}</button>
    </form>

    <form action="{{ route('pin', ['bot' => $bot]) }}" method="post" id="pin_form" class="mt-5">
        @csrf
        <h3>{{ __('webapp.pin_link') }}</h3>
        <div class="select-group mb-3">
            <label for="pin_channel" class="form-label">{{ __('webapp.pin_channel') }}</label>
            <select class="form-select" name="pin_channel" id="pin_channel">
                <option>{{ __('webapp.choose_channel') }}</option>
                @foreach($bot->channels as $channel)
                    @if($channel->type == 'tg')
                        <option value="{{ $channel->tg_id }}">{{ $channel->name }}</option>
                    @endif
                @endforeach
            </select>
            @error('pin_channel')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label for="pin_button" class="form-label">{{ __('webapp.pin_button') }}</label>
            <input type="text" class="form-control" id="pin_button" name="pin_button">
            @error('pin_button')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label for="pin-text" class="form-label">{{ __('webapp.pin_text') }}</label>
            <textarea class="form-control" name="pin_text" id="pin-text" rows="3"></textarea>
            @error('pin_text')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" form="pin_form" class="btn btn-primary">{{ __('webapp.pin') }}</button>
    </form>
@endsection