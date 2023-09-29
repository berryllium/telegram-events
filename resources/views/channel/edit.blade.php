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
            <label for="tg_id" class="form-label">{{ __('webapp.tg_id') }}</label>
            <input type="number" class="form-control" id="tg_id" name="tg_id" value="{{ $channel->tg_id }}">
            @error('tg_id')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
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