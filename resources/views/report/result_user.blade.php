@extends('base')
@section('title', $title)
@section('content')
    <h3 class="text-center mb-4">{{ $period }}</h3>
    <table class="table table-striped d-block d-md-table overflow-x-auto">
        <tr>
            <th>{{ __('webapp.user') }}</th>
            <th>{{ __('webapp.reports.edited_messages') }}</th>
            <th>{{ __('webapp.reports.deleted_messages') }}</th>
        </tr>
        @foreach($users as $user)
            <tr>
                <td><a href="{{ $user['link'] }}">{{ $user['name'] }}</a></td>
                <td>{{ count($user['edit']) }}</td>
                <td>{{ count($user['delete']) }}</td>
            </tr>
        @endforeach
        <tr class="table-dark">
            <td>{{ __('webapp.total') }}</td>
            <td>{{ count($total['edit']) }}</td>
            <td>{{ count($total['delete']) }}</td>
        </tr>
    </table>
@endsection