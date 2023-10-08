@extends('component.filter')

@section('filters')
    <div class="col-lg-3 col-6 my-1">
        <label class="visually-hidden" for="autoSizingInput">{{ __('webapp.search') }}</label>
        <input type="text" class="form-control" id="autoSizingInput" name="search" placeholder="{{ __('webapp.search') }}" value="{{ request('search') }}">
    </div>
    <div class="col-lg-3 col-6 my-1">
        <label class="visually-hidden" for="autoSizingSelect"></label>
        <select class="form-select" id="autoSizingSelect" name="status">
            <option selected value="">{{ __('webapp.status') }}</option>
            @foreach($statuses as $id => $status)
                <option value="{{ $id }}" {{ $id == request('status') ? 'selected' : '' }}>{{ __("webapp.$id") }}</option>
            @endforeach
        </select>
    </div>
@endsection