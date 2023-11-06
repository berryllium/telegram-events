@extends('base')
@section('content')
    <h2 class="text-center">{{ $exception->getMessage() }}</h2>
@endsection
