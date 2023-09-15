@extends('base')
@section('title', 'Создание поля формы')
@section('content')
    <form method="post" action="{{ route('field.store', ['form' => $form]) }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Название поля формы</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
            @error('name')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="code" class="form-label">Код поля формы</label>
            <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}">
            @error('code')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Тип поля</label>
            <select class="form-select" id="type" name="type">
                @foreach(\App\Models\Field::$types as $id => $type)
                    <option value="{{ $id }}">{{ $type }}</option>
                @endforeach
            </select>
            @error('description')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Создать</button>
    </form>
@endsection