<table class="table table-striped d-block d-md-table overflow-x-auto">
    <tr>
        <th>#</th>
        <th>{{ __('webapp.messages.scheduled_dispatch_time') }}</th>
        <th>{{ __('webapp.messages.real_dispatch_time') }}</th>
        <th>{{ __('webapp.status') }}</th>
        <th>{{ __('webapp.channels') }}</th>
        <th>{{ __('webapp.error') }}</th>
        <th class="action-cell">{{ __('webapp.actions') }}</th>
    </tr>
    @php $counter = 1; @endphp
    @foreach($schedules as $schedule)
        <tr class="{{ $schedule->trashed() ? 'opacity-25' : '' }}">
            <td>{{ $counter ++ }}</td>
            <td>{{ $schedule->sending_date }}</td>
            <td>{{ $schedule->status == 'success' ? $schedule->updated_at : '' }}</td>
            <td class="table-{{ $schedule->status_class }}">{{ $schedule->status_name }}</td>
            <td>
                @foreach($schedule->channels as $channel)
                    <p>{{ $channel->name }}</p>
                @endforeach
            </td>
            <td class="text-wrap">{{ $schedule->error_text }}</td>
            <td class="align-middle text-nowrap">
                <a href="{{ route('message_schedule.edit', $schedule) }}" class="btn btn-primary m-1">
                    <i class="bi bi-pen" role="button"></i>
                </a>
                @if(!$schedule->trashed())
                <form action="{{ route('message_schedule.destroy', $schedule) }}" method="post" class="d-inline m-1" data-action="delete" data-text="{{ __('webapp.deletion_confirm') }}">
                    @csrf
                    @method('delete')
                    <button class="btn btn-danger" type="submit">
                        <i class="bi bi-trash" role="button" onclick="this.parentNode.submit()"></i>
                    </button>
                </form>
                @endif
            </td>
        </tr>
    @endforeach
</table>