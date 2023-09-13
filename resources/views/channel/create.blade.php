@extends('base')
@section('title', 'Добавление ТГ-канала')
@section('content')
    <form method="post" action="{{ route('channel.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Название канала</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
            @error('name')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="tg_id" class="form-label">Телеграм ID</label>
            <input type="number" class="form-control" id="tg_id" name="tg_id" value="{{ old('tg_id') }}">
            @error('tg_id')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="form" class="form-label">Форма WebApp</label>
            <select class="form-select" id="form" name="form">
                <option value=""></option>
                @foreach(\App\Models\Form::all() as $form)
                    <option value="{{ $form->id }}" {{ $form->id == old('form') ? 'selected' : ''}}>{{ $form->name }}</option>
                @endforeach
            </select>
            @error('form')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <textarea class="form-control" id="description" name="description" rows="5">{{ old('description') }}</textarea>
            @error('description')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Создать</button>
    </form>
@endsection