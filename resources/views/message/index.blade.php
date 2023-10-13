@extends('base')
@section('title', __('webapp.messages.title'))
@section('content')

    @include('message.component.filter')

    <table class="table table-striped d-block d-md-table overflow-x-auto">
        <tr>
            <th>{{ __('webapp.author') }}</th>
            <th>{{ __('webapp.message_text') }}</th>
            <th>{{ __('webapp.sending_time') }}</th>
            <th>{{ __('webapp.status') }}</th>
            <th class="action-cell">{{ __('webapp.actions') }}</th>
        </tr>

        @foreach($schedules as $schedule)
            <tr>
                <td>{{ $schedule->message->author->name }}</td>
                <td>{{ strip_tags($schedule->message->text) }}</td>
                <td>{{ $schedule->sending_date }}</td>
                <td class="table-{{ $schedule->status_class }}">{{ $schedule->status_name }}</td>
                <td class="align-middle text-nowrap">
                    @if(auth()->user()->hasAnyRole('supervisor', 'admin'))
                        <form action="{{ route('message_schedule.destroy', $schedule) }}" method="post" class="d-inline m-1" data-action="delete" data-text="{{ __('webapp.deletion_confirm') }}">
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