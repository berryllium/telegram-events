@extends('base')
@section('title', __('webapp.users.edit'))
@section('content')
    <form method="post" action="{{ route('user.update', $user) }}">
        @csrf
        @method('put')
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('webapp.name') }}</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
            @error('name')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('webapp.email') }}</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}">
            @error('email')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('webapp.password') }}</label>
            <input type="password" class="form-control" id="password" name="password" value="">
            @error('password')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">{{ __('webapp.confirm_password') }}</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" value="">
            @error('confirm_password')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">{{ __('webapp.roles.roles') }}</label>
            <select class="form-select" id="type" name="roles[]" multiple>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ $user->roles->contains($role->id) ? 'selected' : '' }}>{{ $role->name }}</option>
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
                    <option value="{{ $bot->id }}" {{ $user->telegram_bots->contains($bot->id) ? 'selected' : '' }}>{{ $bot->name }}</option>
                @endforeach
            </select>
            @error('bots')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary"> {{ __('webapp.update') }}</button>
    </form>
@endsection