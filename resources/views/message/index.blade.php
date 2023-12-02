@extends('base')
@section('title', __('webapp.messages.title'))
@section('content')

    @include('message.component.filter')

    <table class="table table-striped d-block d-md-table overflow-x-auto">
        <tr>
            <th>#</th>
            <th>{{ __('webapp.author') }}</th>
            <th>{{ __('webapp.places.place') }}</th>
            <th>{{ __('webapp.message_text') }}</th>
            <th>{{ __('webapp.sending_time') }}</th>
            <th class="action-cell">{{ __('webapp.actions') }}</th>
        </tr>

        @foreach($messages as $message)
            <tr class="{{ $message->trashed() ? 'opacity-25' : '' }}">
                <td>{{ $message->id }}</td>
                <td>{{ $message->author?->name }}</td>
                <td>{{ $places[$message->data->place] }}</td>
                <td>{{ Str::of(strip_tags($message->text))->limit(50) }}</td>
                <td>
                    <table class="table">
                        @foreach($message->message_schedules as $schedule)
                            <tr class=" {{ $schedule->trashed() ? 'opacity-25' : '' }}">
                                <td class="col-4">{{ $schedule->sending_date }}</td>
                                <td class="col-4 text-center table-{{ $schedule->status_class }}">{{ $schedule->status_name }}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
                <td class="align-middle text-nowrap">
                    <a href="{{ route('message.edit', $message) }}" class="btn btn-primary m-1">
                        <i class="bi bi-pen" role="button"></i>
                    </a>
                    @if(!$message->trashed() and auth()->user()->hasAnyRole('supervisor', 'admin'))
                        <form action="{{ route('message.destroy', $message) }}" method="post" class="d-inline m-1" data-action="delete" data-text="{{ __('webapp.deletion_confirm') }}">
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
    <div class="row"><div class="col-6">{{ $messages->links() }}</div></div>

@endsection