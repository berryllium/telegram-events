@extends('base')
@section('title', __('webapp.dictionaries.add'))
@section('content')
    <form method="post" action="{{ route('dictionary.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('webapp.title') }}</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
            @error('name')
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
        <button type="submit" class="btn btn-primary">{{ __('webapp.create') }}</button>
    </form>
@endsection