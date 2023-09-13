@extends('base')
@section('title', 'Редактирование веб-формы')
@section('content')
    <form method="post" action="{{ route('form.update', [$form]) }}">
        <input type="hidden" name="tg_channel" value="1">
        @csrf
        @method('put')
        <div class="mb-3">
            <label for="name" class="form-label">Название формы</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $form->name }}">
            @error('name')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <textarea class="form-control" id="description" name="description" rows="5">{{ $form->description }}</textarea>
            @error('description')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <a href="{{ route('field.index', [$form]) }}">Поля формы</a>
        </div>
        <button type="submit" class="btn btn-primary">Обновить</button>
    </form>
@endsection