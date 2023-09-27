@extends('component.filter')

@section('filters')
    <div class="col-auto">
        <label class="visually-hidden" for="autoSizingInput">{{ __('webapp.search') }}</label>
        <input type="text" class="form-control" id="autoSizingInput" name="search" placeholder="{{ __('webapp.search') }}">
    </div>
    <div class="col-auto">
        <label class="visually-hidden" for="autoSizingSelect">Preference</label>
        <select class="form-select" id="autoSizingSelect" name="status">
            <option selected value="">{{ __('webapp.status') }}</option>
            @foreach($statuses as $id => $status)
                <option value="{{ $id }}" {{ $id == request('status') ? 'selected' : '' }}>{{ $status }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <label class="visually-hidden" for="telegram_bot">Preference</label>
        <select class="form-select" id="telegram_bot" name="telegram_bot">
            <option selected value="">{{ __('webapp.bots.bot') }}</option>
            @foreach($bots as $bot)
                <option value="{{ $bot->id }}" {{ request('telegram_bot') == $bot->id ? 'selected' : '' }}>{{ $bot->name }}</option>
            @endforeach
        </select>
    </div>
@endsection