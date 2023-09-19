@extends('layouts.app')
    @section('navbar')
        <li class="nav-item">
            <a class="nav-link{{ request()->segment(1) == 'bot' ? ' active' : '' }}" href="{{ route('bot.index') }}">Боты</a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ !request()->segment(1) || request()->segment(1) == 'form' ? ' active' : '' }}"  href="{{ route('form.index') }}">Формы</a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ request()->segment(1) == 'channel' ? ' active' : '' }}" href="{{ route('channel.index') }}">Каналы</a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ request()->segment(1) == 'place' ? ' active' : '' }}" href="{{ route('place.index') }}">Места</a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ request()->segment(1) == 'author' ? ' active' : '' }}" href="{{ route('author.index') }}">Авторы</a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ request()->segment(1) == 'message' ? ' active' : '' }}" href="{{ route('message.index') }}">Сообщения</a>
        </li>
    @endsection
@section('messages')
    <div class="col-12">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">{{ $message }}</div>
        @endif
    </div>
@endsection

