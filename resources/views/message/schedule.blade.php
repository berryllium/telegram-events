<table class="table table-striped d-block d-md-table overflow-x-auto">
    <tr>
        <th>#</th>
        <th>Запланированное время отправки</th>
        <th>Фактическое время отправки</th>
        <th>Статус</th>
        <th>Каналы</th>
        <th>Ошибка</th>
        <th class="action-cell">Действия</th>
    </tr>
    @php $counter = 1; @endphp
    @foreach($schedules as $schedule)
        <tr>
            <td>{{ $counter ++ }}</td>
            <td>{{ $schedule->sending_date }}</td>
            <td>{{ $schedule->status == 'success' ? $schedule->updated_at : '' }}</td>
            <td>{{ $schedule->status_name }}</td>
            <td>
                @foreach($schedule->telegram_channels as $channel)
                    <p>{{ $channel->name }}</p>
                @endforeach
            </td>
            <td>{{ $schedule->error }}</td>
            <td class="align-middle text-nowrap">
                <a href="{{ route('message_schedule.edit', $schedule) }}" class="btn btn-primary m-1">
                    <i class="bi bi-pen" role="button"></i>
                </a>
                <form action="{{ route('message_schedule.destroy', $schedule) }}" method="post" class="d-inline m-1" data-action="delete">
                    @csrf
                    @method('delete')
                    <button class="btn btn-danger" type="submit">
                        <i class="bi bi-trash" role="button" onclick="this.parentNode.submit()"></i>
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
</table>