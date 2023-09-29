@extends('base')
@section('title',  __('webapp.sending_title', ['message' => $schedule->message->id]))
@section('content')
    <form method="post" action="{{ route('message_schedule.update', $schedule) }}">
        @csrf
        @method('put')
        <div class="mb-3">
            <label for="status" class="form-label">{{ __('webapp.status') }}</label>
            <select id="status" name="status" class="form-select">
                @foreach(\App\Models\MessageSchedule::$statuses as $status => $statusName)
                    <option value="{{ $status }}" {{ $status == $schedule->status ? 'selected' : '' }}>{{ __("webapp.$status") }}</option>
                @endforeach
            </select>
            @error('status')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="sending_date" class="form-label">{{ __('webapp.sending_time') }}</label>
            <input id="sending_date" type="datetime-local" class="form-control" name="sending_date" value="{{ $schedule->sending_date }}">
        </div>
        <button type="submit" class="btn btn-primary">{{ __('webapp.update') }}</button>
    </form>
@endsection