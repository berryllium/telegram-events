@extends('layouts.app')
    @section('navbar')
        <li class="nav-item">
            <a class="nav-link"  href="{{ route('form.index') }}">Формы</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('channel.index') }}">Каналы</a>
        </li>
    @endsection
@section('messages')
    <div class="col-12">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">{{ $message }}</div>
        @endif
    </div>
@endsection

