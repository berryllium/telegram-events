@extends('base')
@section('title', __('webapp.authors.edit'))
@section('content')
    <form method="post" action="{{ route('author.update', $author) }}">
        @csrf
        @method('put')
        @if($author->username)
            <div class="mb-3">{{ __('webapp.login') }} - <a href="https://t.me/{{ $author->username }}" target="_blank">{{ $author->username }}</a></div>
        @endif
        <div class="mb-3">
            <label for="title" class="form-label">{{ __('webapp.name') }}</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ $pivot->title }}">
            @error('title')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">{{ __('webapp.description') }}</label>
            <textarea class="form-control" id="description" name="description" rows="5">{{ $pivot->description }}</textarea>
            @error('description')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label for="form" class="form-label">{{ __('webapp.places.places') }}</label>
            <select class="form-select" id="form" name="places[]" multiple data-select="2">
                <option value=""></option>
                @foreach($places as $place)
                    <option value="{{ $place->id }}" {{ $author->places->contains($place->id) ? 'selected' : ''}}>{{ $place->name }}</option>
                @endforeach
            </select>
            @error('form')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
            <div class="btn btn-warning" data-role="selectAll">{{ __('webapp.select_all') }}</div>
        </div>
        <div class="mb-2">
            <input type="hidden" name="can_select_channels" value="0">
            <input id="can_select_channels" type="checkbox" class="form-check-input" name="can_select_channels" value="1" {{ $pivot->can_select_channels ? 'checked' : '' }}>
            <label class="form-check-label" for="can_select_channels">{{ __('webapp.can_select_channels') }}</label>
        </div>
        <div class="mb-4">
            <label for="channels" class="form-label">{{ __('webapp.channels') }}</label>
            <select class="form-select" id="channels" name="channels[]" multiple data-select="2">
                <option value=""></option>
                @foreach($channels as $channel)
                    <option value="{{ $channel->id }}" {{ $author->channels->contains($channel->id) ? 'selected' : ''}}>{{ $channel->name }}</option>
                @endforeach
            </select>
            @error('form')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
            <div class="btn btn-warning" data-role="selectAll">{{ __('webapp.select_all') }}</div>
        </div>
        <div class="mb-2">
            <input type="hidden" name="trusted" value="0">
            <input id="trusted" type="checkbox" class="form-check-input" name="trusted" value="1" {{ $pivot->trusted ? 'checked' : '' }}>
            <label class="form-check-label" for="trusted">{{ __('webapp.trusted_author') }}</label>
        </div>
        <div class="my-3">
            <input type="hidden" name="can_use_gigachat" value="0">
            <input id="can_use_gigachat" type="checkbox" class="form-check-input" name="can_use_gigachat" value="1" {{ $pivot->can_use_gigachat ? 'checked' : '' }}>
            <label class="form-check-label" for="can_use_gigachat">{{ __('webapp.can_use_gigachat') }}</label>
        </div>
        <button type="submit" class="btn btn-primary">{{ __('webapp.update') }}</button>
    </form>
@endsection