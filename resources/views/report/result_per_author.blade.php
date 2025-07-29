@extends('base')
@section('title', $title)
@section('content')
    <h3 class="text-center mb-4">{{ $period }}</h3>

    <h4 class="text-center mb-4">Статистика по каналам</h4>
    <table class="table table-striped d-block d-md-table overflow-x-auto">
        <tr>
            <th>Канал</th>
            <th>Опубликовано</th>
            <th>В ожидании</th>
            <th>Ошибка</th>
        </tr>
        @foreach($statistics as $ch => $row)
            <tr>
                <td>{{ $ch }}</td>
                <td>{{ $row['success'] ?? '' }}</td>
                <td>{{ $row['wait'] ?? '' }}</td>
                <td>{{ $row['error'] ?? '' }}</td>
            </tr>
        @endforeach
        <tr class="table-dark">
            <td>Итого</td>
            <td>{{ $total['success'] ?? '' }}</td>
            <td>{{ $total['wait'] ?? '' }}</td>
            <td>{{ $total['error'] ?? '' }}</td>
        </tr>
    </table>

    <h4 class="text-center mb-4">Подробный отчет</h4>
    <table class="table table-striped d-block d-md-table overflow-x-auto">
        <tr>
            <th>Канал</th>
            <th>Сcылка в админке</th>
            <th>Ссылка в канале</th>
            <th>Дата публикации</th>
        </tr>
        @foreach($posts as $post)
            <tr>
                <td><a href="{{ $post['channelLink'] }}">{{ $post['channelName'] }}</a></td>
                <td><a href="{{ $post['messageLink'] }}">сообщение</a></td>
                <td> @if($post['postLink']) <a href="{{ $post['postLink'] }}">пост</a> @endif</td>
                <td>{{ $post['date'] }}</td>
            </tr>
        @endforeach
        <tr class="table-dark">
            <td>{{ __('webapp.total') }}</td>
            <td>{{ count($posts) }}</td>
            <td></td>
            <td></td>
        </tr>
    </table>
@endsection