@extends('base')
@section('title', $title)
@section('content')
    <h3 class="text-center mb-4"></h3>
    <table class="table table-striped d-block d-md-table overflow-x-auto">
        <tr>
            <th>#</th>
            <th>Канал</th>
            <th>Ссылка на сообщение</th>
            <th>Ссылка на пост</th>
            <th>Ошибка</th>
            <th>Отправлено</th>
            <th>Дата</th>
        </tr>
        @foreach ($posts as $i => $post)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><a href="{{ $post['channelLink'] }}">{{ $post['channelName'] }}</a></td>
                <td><a href="{{ $post['messageLink'] }}">сообщение</a></td>
                <td> @if($post['postLink']) <a href="{{ $post['postLink'] }}">пост</a> @endif</td>
                <td>{{ $post['error'] }}</td>
                <td>{{ $post['sent'] ? 'Да' : 'Нет' }}</td>
                <td>{{ $post['date'] }}</td>
            </tr>
        @endforeach
    </table>
@endsection
