@extends('base')
@section('title',  __('webapp.sending_title', ['message' => $schedule->message->id]))
@section('content')
    <form method="post" action="{{ route('message_schedule.update', $schedule) }}">
        @csrf
        @method('put')
        <div class="mb-4">
            <table class="table table-striped d-block d-md-table overflow-x-auto">
                <tr>
                    <th>{{ __('webapp.channels') }}</th>
                    <th>{{ __('webapp.status') }}</th>
                    <th>{{ __('webapp.link') }}</th>
                    <th>{{ __('webapp.error') }}</th>
                    @if(!$schedule->trashed())
                        <th>{{ __('webapp.retry') }}</th>
                        <th>{{ __('webapp.delete') }}</th>
                    @endif
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
                        @if(!$schedule->trashed())
                        <td>
                            @if($channel->pivot->error)
                                <label><input type="radio" name="act[{{ $channel->id }}]" value="retry"></label>
                            @endif
                        </td>
                        <td>
                            <label><input type="radio" name="act[{{ $channel->id }}]" value="delete"></label>
                        </td>
                        @endif
                    </tr>
                @endforeach
            </table>
        </div>
        @if(!$schedule->trashed() && $schedule->status == 'wait')
            <div class="mb-3">
                <label for="new_channels">{{ __('webapp.add_channel') }}</label>
                <select name="new_channels[]" id="new_channels" class="form-select" data-select="2" data-search="true" multiple>
                    @foreach($channels as $ch)
                        <option value="{{ $ch->id }}">{{ $ch->name }} ({{ $ch->type }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <input type="checkbox" name="all_channels" class="form-check-input" id="all_channels">
                <label for="all_channels" class="form-check-label">{{ __('webapp.add_all_bot_channels') }}</label>
            </div>
        @endif
        <div class="mb-3">
            <label for="sending_date" class="form-label">{{ __('webapp.sending_time') }}</label>
            <input id="sending_date" type="datetime-local" class="form-control" name="sending_date" value="{{ \Illuminate\Support\Carbon::parse(null)->format('Y-m-d H:i:00') }}">
        </div>
        @if(!$schedule->trashed())
            <button type="submit" class="btn btn-primary">{{ __('webapp.update') }}</button>
        @endif
    </form>
@endsection