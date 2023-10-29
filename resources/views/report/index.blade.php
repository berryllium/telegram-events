@extends('base')
@section('title', __('webapp.report'))
@section('content')

    <form action="{{ route('report.process') }}" class="col-lg-6 col-12 m-auto">
        <div class="mb-3">
            <label for="type" class="form-label">{{ __('webapp.type') }}</label>
            <select name="type" id="type" class="form-select">
                <option value="author">{{ __('webapp.author') }}</option>
                <option value="place">{{ __('webapp.places.place') }}</option>
            </select>
            @error('type')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <div class="input-group">
                <label class="w-50">{{ __('webapp.reports.from') }}</label>
                <label class="w-50">{{ __('webapp.reports.to') }}</label>
            </div>
            <div class="input-group">
                <label class="d-none" for="from"></label>
                <label class="d-none" for="to"></label>
                <input type="datetime-local" class="form-control @error('from') is-invalid @enderror" name="from" id="from" value="{{ old('from') }}">
                <input type="datetime-local" class="form-control @error('to') is-invalid @enderror" name="to" id="to" value="{{ old('to') }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">{{ __('webapp.reports.get') }}</button>
    </form>

@endsection