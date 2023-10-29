@extends('component.filter')

@section('filters')
    <div class="col-lg-2 col-6 my-1">
        <label class="visually-hidden" for="from">{{ __('webapp.from') }}</label>
        <input type="datetime-local" class="form-control" id="from" name="from" placeholder="{{ __('webapp.from') ?: 'from' }}" value="{{ request('from') }}">
    </div>
    <div class="col-lg-2 col-6 my-1">
        <label class="visually-hidden" for="to">{{ __('webapp.to') }}</label>
        <input type="datetime-local" class="form-control" id="to" name="to" placeholder="{{ __('webapp.to') }}" value="{{ request('to') }}">
    </div>
    <div class="col-lg-2 col-6 my-1">
        <label class="visually-hidden" for="autoSizingInput">{{ __('webapp.search') }}</label>
        <input type="text" class="form-control" id="autoSizingInput" name="search" placeholder="{{ __('webapp.search') }}" value="{{ request('search') }}">
    </div>
    <div class="col-lg-2 col-6 my-1">
        <label class="visually-hidden" for="autoSizingSelect"></label>
        <select class="form-select" id="autoSizingSelect" name="status">
            <option selected value="">{{ __('webapp.status') }}</option>
            @foreach(\App\Models\MessageSchedule::$statusMap as $status => $class)
                <option value="{{ $status }}" {{ $status == request('status') ? 'selected' : '' }}>{{ __("webapp.$status") }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-2 col-6 my-1">
        <div class="form-check">
            <input id="deleted" class="form-check-input" type="checkbox" value="1" name="deleted" {{ request('deleted') ? 'checked' : '' }}>
            <label class="form-check-label" for="deleted">
                {{ __('webapp.show_deleted') }}
            </label>
        </div>
    </div>

@endsection