@extends('base')
@section('title',  __('webapp.sending_title', ['message' => $schedule->message->id]))
@section('content')
    <form method="post" action="{{ route('message_schedule.update', $schedule) }}">
        @csrf
        @method('put')
        <div class="mb-3">
            <table class="table table-striped d-block d-md-table overflow-x-auto">
                <tr>
                    <th>{{ __('webapp.channels') }}</th>
                    <th>{{ __('webapp.status') }}</th>
                    <th>{{ __('webapp.link') }}</th>
                    <th>{{ __('webapp.error') }}</th>
                    <th>{{ __('webapp.retry') }}</th>
                    <th>{{ __('webapp.delete') }}</th>
                </tr>
                @foreach($schedule->channels as $channel)
                    <tr>
                        <td>{{ $channel->name }}</td>
                        <td>
                            @if($channel->pivot->error)
                                {{ __('webapp.error') }}
                            @elseif($channel->pivot->sent)
                                {{ __('webapp.success') }}
                            @else
                                {{ __('webapp.process') }}
                            @endif
                        </td>
                        <td>{!! $channel->pivot->link !!}</td>
                        <td>{{ $channel->pivot->error }}</td>
                        <td>
                            @if($channel->pivot->error)
                                <label><input type="radio" name="act[{{ $channel->id }}][retry]"></label>
                            @endif
                        </td>
                        <td>
                            @if($channel->pivot->error)
                                <label><input type="radio" name="act[{{ $channel->id }}][delete]"></label>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="mb-3">
            <label for="sending_date" class="form-label">{{ __('webapp.sending_time') }}</label>
            <input id="sending_date" type="datetime-local" class="form-control" name="sending_date" value="{{ $schedule->sending_date }}">
        </div>
        <button type="submit" class="btn btn-primary">{{ __('webapp.update') }}</button>
    </form>
@endsection