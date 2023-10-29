@extends('base')
@section('title', $title)
@section('content')
    <h3 class="text-center mb-4">{{ $period }}</h3>
    <table class="table table-striped d-block d-md-table overflow-x-auto">
        <tr>
            <th>{{ __('webapp.places.place') }}</th>
            <th>{{ __('webapp.reports.total_messages') }}</th>
            <th>{{ __('webapp.reports.total_sending') }}</th>
            <th>{{ __('webapp.reports.success_sending') }}</th>
            <th>{{ __('webapp.reports.wait_sending') }}</th>
            <th>{{ __('webapp.reports.error_sending') }}</th>
        </tr>
        @foreach($places as $place)
            <tr>
                <td><a href="{{ $place['link'] }}">{{ $place['name'] }}</a></td>
                <td>{{ $place['total_messages'] }}</td>
                <td>{{ $place['total_sending'] }}</td>
                <td>{{ $place['success_sending'] }}</td>
                <td>{{ $place['wait_sending'] }}</td>
                <td>{{ $place['error_sending'] }}</td>
            </tr>
        @endforeach
    </table>
@endsection