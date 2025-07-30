@extends('base')
@section('title', $title)
@section('content')
    <h3 class="text-center mb-4">{{ $from }} - {{ $to }}</h3>
    <table class="table table-striped d-block d-md-table overflow-x-auto">
        <tr>
            <th>{{ __('webapp.places.place') }}</th>
            <th>{{ __('webapp.reports.total_messages') }}</th>
            <th>{{ __('webapp.reports.total_sending') }}</th>
            <th>{{ __('webapp.reports.success_sending') }}</th>
            <th>{{ __('webapp.reports.wait_sending') }}</th>
            <th>{{ __('webapp.reports.error_sending') }}</th>
            <th></th>
        </tr>
        @foreach ($places as $place)
            <tr>
                <td><a href="{{ $place['link'] }}">{{ $place['name'] }}</a></td>
                <td>{{ $place['total_messages'] }}</td>
                <td>{{ $place['total_sending'] }}</td>
                <td>{{ $place['success_sending'] }}</td>
                <td>{{ $place['wait_sending'] }}</td>
                <td>{{ $place['error_sending'] }}</td>
                <td>
                    <x-detailed-table :url="route('report.process', ['place' => $place['id']])" :payload="['from' => $from, 'to' => $to, 'type' => 'PlaceByAuthor' ]" text="По авторам" />
                    <x-detailed-table :url="route('report.process', ['place' => $place['id']])" :payload="['from' => $from, 'to' => $to, 'type' => 'PlaceByChannel']" text="По каналам" />
                </td>
            </tr>
        @endforeach
        <tr class="table-dark">
            <td>{{ __('webapp.total') }}</td>
            <td>{{ $total['total_messages'] }}</td>
            <td>{{ $total['total_sending'] }}</td>
            <td>{{ $total['success_sending'] }}</td>
            <td>{{ $total['wait_sending'] }}</td>
            <td>{{ $total['error_sending'] }}</td>
            <td></td>
        </tr>
    </table>
@endsection
