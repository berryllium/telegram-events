@extends('base')
@section('title', __('webapp.add_channel'))
@section('content')
    <form method="post" action="{{ route('channel.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('webapp.channel_name') }}</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
            @error('name')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">{{ __('webapp.channel_type') }}</label>
            <select id="type" name="type" class="form-select">
                @foreach(\App\Models\Channel::$types as $type)
                    <option value="{{ $type }}" {{ $type == old('type') ? 'selected' : '' }}>{{ __("webapp.channel_$type") }}</option>
                @endforeach
            </select>
            @error('type')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="tg_id" class="form-label">{{ __('webapp.channel_id') }}</label>
            <input type="text" class="form-control" id="tg_id" name="tg_id" value="{{ old('tg_id') }}">
            @error('tg_id')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="token" class="form-label">{{ __('webapp.api_token') }}</label>
            <input type="text" class="form-control" id="token" name="token" value="{{ old('token') }}">
            @error('token')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <input type="hidden" name="show_place" value="0">
            <input id="show_place" type="checkbox" class="form-check-input" name="show_place" value="1" checked>
            <label class="form-check-label" for="show_place">{{ __('webapp.show_place') }}</label>
        </div>
        <div class="mb-3">
            <input type="hidden" name="show_address" value="0">
            <input id="show_address" type="checkbox" class="form-check-input" name="show_address" value="1" checked>
            <label class="form-check-label" for="show_address">{{ __('webapp.show_address') }}</label>
        </div>
        <div class="mb-3">
            <input type="hidden" name="show_work_hours" value="0">
            <input id="show_work_hours" type="checkbox" class="form-check-input" name="show_work_hours" value="1" checked>
            <label class="form-check-label" for="show_work_hours">{{ __('webapp.show_work_hours') }}</label>
        </div>
        <div class="mb-3">
            <input type="hidden" name="show_links" value="0">
            <input id="show_links" type="checkbox" class="form-check-input" name="show_links" value="1" checked>
            <label class="form-check-label" for="show_links">{{ __('webapp.show_links') }}</label>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">{{ __('webapp.description') }}</label>
            <textarea class="form-control" id="description" name="description" rows="5">{{ old('description') }}</textarea>
            @error('description')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <x-channel-links />
        </div>
        <button type="submit" class="btn btn-primary">{{ __('webapp.add') }}</button>
    </form>
@endsection