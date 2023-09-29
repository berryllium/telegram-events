@extends('base')
@section('title', __('webapp.authors.edit'))
@section('content')
    <form method="post" action="{{ route('author.update', $author) }}">
        @csrf
        @method('put')
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('webapp.name') }}</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $author->name }}">
            @error('name')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">{{ __('webapp.login') }}</label>
            <input type="text" class="form-control" id="username" name="username" value="{{ $author->username }}">
            @error('username')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="tg_id" class="form-label">{{ __('webapp.tg_id') }}</label>
            <input type="number" class="form-control" id="tg_id" name="tg_id" value="{{ $author->tg_id }}">
            @error('tg_id')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="form" class="form-label">{{ __('webapp.places.places') }}</label>
            <select class="form-select" id="form" name="places[]" multiple data-select="2">
                <option value=""></option>
                @foreach($places as $place)
                    <option value="{{ $place->id }}" {{ $author->places->contains($place->id) ? 'selected' : ''}}>{{ $place->name }} ({{ $place->form->name }})</option>
                @endforeach
            </select>
            @error('form')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-check mb-3">
            <input type="hidden" name="trusted" value="0">
            <input id="trusted" type="checkbox" class="form-check-input" name="trusted" value="1" {{ $author->trusted ? 'checked' : '' }}>
            <label class="form-check-label" for="trusted">{{ __('webapp.trusted_author') }}</label>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">{{ __('webapp.description') }}</label>
            <textarea class="form-control" id="description" name="description" rows="5">{{ $author->description }}</textarea>
            @error('description')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">{{ __('webapp.update') }}</button>
    </form>
@endsection