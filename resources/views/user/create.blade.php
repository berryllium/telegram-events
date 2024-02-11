@extends('base')
@section('title', __('webapp.users.add'))
@section('content')
    <form method="post" action="{{ route('user.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('webapp.name') }}</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
            @error('name')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('webapp.email') }}</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
            @error('email')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('webapp.password') }}</label>
            <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}">
            @error('password')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">{{ __('webapp.confirm_password') }}</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}">
            @error('password_confirmation')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">{{ __('webapp.roles.roles') }}</label>
            <select class="form-select" id="type" name="roles[]" multiple>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('roles') && in_array($role->id, old('roles')) ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
            @error('roles')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">{{ __('webapp.bots.bots') }}</label>
            <select class="form-select" id="type" name="bots[]" multiple>
                @foreach($bots as $bot)
                    <option value="{{ $bot->id }}" {{ old('bots') && in_array($bot->id, old('bots')) ? 'selected' : '' }}>{{ $bot->name }}</option>
                @endforeach
            </select>
            @error('bots')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">{{ __('webapp.places.list') }}</label>
            <select class="form-select" id="type" name="places[]" multiple>
                @foreach($places as $place)
                    <option value="{{ $place->id }}" {{ old('places') && in_array($bot->id, old('places')) ? 'selected' : '' }}>{{ $place->name }}</option>
                @endforeach
            </select>
            @error('places')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary"> {{ __('webapp.create') }}</button>
    </form>
@endsection