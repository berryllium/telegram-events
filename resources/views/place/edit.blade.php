@extends('base')
@section('title', __('webapp.places.edit'))
@section('content')
    <form method="post" action="{{ route('place.update', [$place]) }}">
        @csrf
        @method('put')
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('webapp.title') }}</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $place->name }}">
            @error('name')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">{{ __('webapp.address') }}</label>
            <input type="text" class="form-control" id="address" name="address" value="{{ $place->address }}">
            @error('address')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="working_hours" class="form-label">{{ __('webapp.working_hours') }}</label>
            <input type="text" class="form-control" id="working_hours" name="working_hours" value="{{ $place->working_hours }}">
            @error('working_hours')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="additional_info" class="form-label">{{ __('webapp.additional_info') }}</label>
            <textarea class="form-control" id="additional_info" name="additional_info" rows="5">{{ $place->additional_info }}</textarea>
            @error('additional_info')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="channels" class="form-label">{{ __('webapp.channels') }}</label>
            <select class="form-select" id="channels" name="channels[]" multiple data-select="2">
                @foreach(\App\Models\Channel::where('telegram_bot_id', session('bot'))->get() as $channel)
                    <option value="{{ $channel->id }}" {{ $place->channels->contains($channel->id) ? 'selected' : ''}}>
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
            <textarea class="form-control" id="description" name="description" rows="5">{{ $place->description }}</textarea>
            @error('description')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">{{ __('webapp.update') }}</button>
    </form>
@endsection