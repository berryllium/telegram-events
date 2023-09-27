@extends('base')
@section('title', 'Сообщения')
@section('content')

    @include('message.component.filter', ['statuses' => $statuses])

    <table class="table table-striped d-block d-md-table overflow-x-auto">
        <tr>
            <th>Автор</th>
            <th>Текст сообщения</th>
            <th>Время отправки</th>
            <th>Статус</th>
            <th>Бот</th>
            <th class="action-cell">Действия</th>
        </tr>

        @foreach($schedules as $schedule)
            <tr>
                <td>{{ $schedule->message->author->name }}</td>
                <td>{{ strip_tags($schedule->message->text) }}</td>
                <td>{{ $schedule->sending_date }}</td>
                <td class="table-{{ $schedule->status_class }}">{{ $schedule->status_name }}</td>
                <td>{{ $schedule->message->telegram_bot->name }}</td>
                <td class="align-middle text-nowrap">
                    @if(auth()->user()->hasAnyRole('supervisor', 'admin'))
                        <form action="{{ route('message_schedule.destroy', $schedule) }}" method="post" class="d-inline m-1" data-action="delete">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger" type="submit">
                                <i class="bi bi-trash" role="button" onclick="this.parentNode.submit()"></i>
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('message.edit', $schedule->message) }}" class="btn btn-primary m-1">
                        <i class="bi bi-pen" role="button"></i>
                    </a>
                </td>
            </tr>
        @endforeach
    </table>
    <div class="row"><div class="col-6">{{ $schedules->links() }}</div></div>

@endsection