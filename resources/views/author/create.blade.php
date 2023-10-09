@extends('base')
@section('title', __('webapp.authors.add'))
@section('content')
    <form method="post" action="{{ route('author.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('webapp.name') }}</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
            @error('name')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">{{ __('webapp.login') }}</label>
            <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}">
            @error('username')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="tg_id" class="form-label">{{ __('webapp.channel_id') }}</label>
            <input type="number" class="form-control" id="tg_id" name="tg_id" value="{{ old('tg_id') }}">
            @error('tg_id')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-check mb-3">
            <input type="hidden" name="trusted" value="0">
            <input id="trusted" type="checkbox" class="form-check-input" name="trusted" value="1" {{ old('trusted') ? 'checked' : '' }}>
            <label class="form-check-label" for="trusted">{{ __('webapp.trusted_author') }}</label>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">{{ __('webapp.description') }}</label>
            <textarea class="form-control" id="description" name="description" rows="5">{{ old('description') }}</textarea>
            @error('description')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">{{ __('webapp.create') }}</button>
    </form>
@endsection