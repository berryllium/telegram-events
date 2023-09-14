@extends('base')
@section('title', 'Добавление ТГ-бота')
@section('content')
    <form method="post" action="{{ route('bot.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Название бота</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
            @error('name')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="code" class="form-label">Символьный код</label>
            <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}">
            @error('code')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="api_token" class="form-label">Ключ API</label>
            <input type="text" class="form-control" id="api_token" name="api_token" value="{{ old('api_token') }}">
            @error('api_token')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="moderation_group" class="form-label">Группа модерации</label>
            <input type="text" class="form-control" id="moderation_group" name="moderation_group" value="{{ old('moderation_group') }}">
            @error('moderation_group')
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