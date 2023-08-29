@extends('base')
@section('title', 'Список ТГ-каналов')
@section('content')

    <a href="{{ route('channel.create') }}" class="btn btn-primary mb-4">Добавить канал</a>

    <table class="table table-striped">
        <tr>
            <th>Название</th>
            <th>Описание</th>
            <th>Телеграм ID</th>
            <th>Действия</th>
        </tr>
        @foreach($channels as $channel)
            <tr>
                <td>{{ $channel->name }}</td>
                <td>{{ $channel->description }}</td>
                <td>{{ $channel->tg_id }}</td>
                <td class="align-middle text-nowrap">
                    <a href="{{ route('channel.edit', $channel) }}" class="btn btn-primary m-1">
                        <i class="bi bi-pen" role="button"></i>
                    </a>
                    <form action="{{ route('channel.destroy', $channel->id) }}" method="post" class="d-inline m-1">
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
    <div class="row">
        <div class="col-6">{{ $channels->links() }}</div>
    </div>
@endsection