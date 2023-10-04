@extends('layouts.app')
    @section('navbar')
        @if(auth()->user()->hasAnyRole('supervisor', 'admin'))
            @if(auth()->user()->hasRole('supervisor'))
                <li class="nav-item">
                    <a class="nav-link{{ request()->segment(1) == 'bot' ? ' active' : '' }}" href="{{ route('bot.index') }}">{{ __('menu.bots') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ request()->segment(1) == 'user' ? ' active' : '' }}" href="{{ route('user.index') }}">{{ __('menu.users') }}</a>
                </li>
                <li class="nav-item me-3">
                    <a class="nav-link{{ !request()->segment(1) || request()->segment(1) == 'form' ? ' active' : '' }}"  href="{{ route('form.index') }}">{{ __('menu.forms') }}</a>
                </li>
            @endif
            @if(session('bot'))
                <li class="nav-item">
                    <a class="nav-link{{ !request()->segment(1) || request()->segment(1) == 'form' ? ' active' : '' }}"  href="{{ route('form.edit', 1) }}">{{ __('menu.form') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ request()->segment(1) == 'channel' ? ' active' : '' }}" href="{{ route('channel.index') }}">{{ __('menu.channels') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ request()->segment(1) == 'place' ? ' active' : '' }}" href="{{ route('place.index') }}">{{ __('menu.places') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ request()->segment(1) == 'author' ? ' active' : '' }}" href="{{ route('author.index') }}">{{ __('menu.authors') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ request()->segment(1) == 'dictionary' ? ' active' : '' }}" href="{{ route('dictionary.index') }}">{{ __('menu.dictionaries') }}</a>
                </li>
            @endif
        @endif
        @if(auth()->user()->hasAnyRole('supervisor', 'admin', 'moderator'))
            <li class="nav-item">
                <a class="nav-link{{ request()->segment(1) == 'message' ? ' active' : '' }}" href="{{ route('message.index') }}">{{ __('menu.messages') }}</a>
            </li>
        @endif
    @endsection
@section('messages')
    <div class="col-12">
        @if($message = Session::get('error'))
            <div class="alert alert-danger">{{ $message }}</div>
        @elseif ($message = Session::get('success'))
            <div class="alert alert-success">{{ $message }}</div>
        @endif
    </div>
@endsection