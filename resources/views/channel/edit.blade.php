@extends('base')
@section('title', __('webapp.edit_channel'))
@section('content')
    <form method="post" action="{{ route('channel.update', [$channel]) }}">
        @csrf
        @method('put')
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('webapp.channel_name') }}</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $channel->name }}">
            @error('name')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">{{ __('webapp.channel_type') }}</label>
            <select id="type" name="type" class="form-select">
                @foreach(\App\Models\Channel::$types as $type)
                    <option value="{{ $type }}" {{ $type == $channel->type ? 'selected' : '' }}>{{ __("webapp.channel_$type") }}</option>
                @endforeach
            </select>
            @error('type')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="tg_id" class="form-label">{{ __('webapp.channel_id') }}</label>
            <input type="number" class="form-control" id="tg_id" name="tg_id" value="{{ $channel->tg_id }}">
            @error('tg_id')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="token" class="form-label">{{ __('webapp.api_token') }}</label>
            <input type="text" class="form-control" id="token" name="token" value="{{ $channel->token }}">
            @error('token')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <input type="hidden" name="show_place" value="0">
            <input id="show_place" type="checkbox" class="form-check-input" name="show_place" value="1" {{ $channel->show_place ? 'checked' : '' }}>
            <label class="form-check-label" for="show_place">{{ __('webapp.show_place') }}</label>
        </div>
        <div class="mb-3">
            <input type="hidden" name="show_address" value="0">
            <input id="show_address" type="checkbox" class="form-check-input" name="show_address" value="1" {{ $channel->show_address ? 'checked' : '' }}>
            <label class="form-check-label" for="show_address">{{ __('webapp.show_address') }}</label>
        </div>
        <div class="mb-3">
            <input type="hidden" name="show_work_hours" value="0">
            <input id="show_work_hours" type="checkbox" class="form-check-input" name="show_work_hours" value="1" {{ $channel->show_work_hours ? 'checked' : '' }}>
            <label class="form-check-label" for="show_work_hours">{{ __('webapp.show_work_hours') }}</label>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">{{ __('webapp.description') }}</label>
            <textarea class="form-control" id="description" name="description" rows="5">{{ $channel->description }}</textarea>
            @error('description')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">{{ __('webapp.update') }}</button>
    </form>
@endsection