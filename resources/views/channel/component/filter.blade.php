@extends('component.filter')

@section('filters')
    <div class="col-lg-2 col-6 my-1">
        <label class="visually-hidden" for="autoSizingInput">{{ __('webapp.search') }}</label>
        <input type="text" class="form-control" id="autoSizingInput" name="search" placeholder="{{ __('webapp.search') }}" value="{{ request('search') }}">
    </div>
@endsection