@extends('component.filter')

@section('filters')
    <div class="col-lg-2 col-6 my-1">
        <label class="visually-hidden" for="autoSizingInput">{{ __('webapp.search') }}</label>
        <input type="text" class="form-control" id="autoSizingInput" name="search" placeholder="{{ __('webapp.search') }}" value="{{ request('search') }}">
    </div>
    <div class="col-lg-2 col-6 my-1">
        <label class="visually-hidden" for="role">Preference</label>
        <select class="form-select" id="role" name="role">
            <option selected value="">{{ __('webapp.role') }}</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>{{ $role->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-2 col-6 my-1">
        <label class="visually-hidden" for="telegram_bot">Preference</label>
        <select class="form-select" id="telegram_bot" name="telegram_bot">
            <option selected value="">{{ __('webapp.bots.bot') }}</option>
            @foreach($bots as $bot)
                <option value="{{ $bot->id }}" {{ request('telegram_bot') == $bot->id ? 'selected' : '' }}>{{ $bot->name }}</option>
            @endforeach
        </select>
    </div>
@endsection