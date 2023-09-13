@extends('base')
@section('title', 'Редактирование места')
@section('content')
    <form method="post" action="{{ route('place.update', [$place]) }}">
        @csrf
        @method('put')
        <div class="mb-3">
            <label for="name" class="form-label">Название места</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $place->name }}">
            @error('name')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Адрес</label>
            <input type="text" class="form-control" id="address" name="address" value="{{ $place->address }}">
            @error('address')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <textarea class="form-control" id="description" name="description" rows="5">{{ $place->description }}</textarea>
            @error('description')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Обновить</button>
    </form>
@endsection