@extends('base')
@section('title', $title)
@section('content')
    <h3 class="text-center mb-4">{{ $period }}</h3>
    <table class="table table-striped d-block d-md-table overflow-x-auto">
        <tr>
            <th>{{ __('webapp.author') }}</th>
            <th>{{ __('webapp.reports.total_messages') }}</th>
            <th>{{ __('webapp.reports.total_sending') }}</th>
            <th>{{ __('webapp.reports.success_sending') }}</th>
            <th>{{ __('webapp.reports.wait_sending') }}</th>
            <th>{{ __('webapp.reports.error_sending') }}</th>
        </tr>
        @foreach($authors as $author)
            <tr>
                <td><a href="{{ $author['link'] }}">{{ $author['name'] }}</a></td>
                <td>{{ $author['total_messages'] }}</td>
                <td>{{ $author['total_sending'] }}</td>
                <td>{{ $author['success_sending'] }}</td>
                <td>{{ $author['wait_sending'] }}</td>
                <td>{{ $author['error_sending'] }}</td>
            </tr>
        @endforeach
        <tr class="table-dark">
            <td>{{ __('webapp.total') }}</td>
            <td>{{ $total['total_messages'] }}</td>
            <td>{{ $total['total_sending'] }}</td>
            <td>{{ $total['success_sending'] }}</td>
            <td>{{ $total['wait_sending'] }}</td>
            <td>{{ $total['error_sending'] }}</td>
        </tr>
    </table>
@endsection