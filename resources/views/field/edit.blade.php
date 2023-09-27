@extends('base')
@section('title', 'Редактирование поля формы')
@section('content')
    @php /** @var \App\Models\Field $field */ @endphp
    <form method="post" action="{{ route('field.update', ['form' => $form, 'field' => $field]) }}">
        @csrf
        @method('put')
        <div class="mb-3">
            <label for="name" class="form-label">Название поля формы</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $field->name }}">
            @error('name')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="code" class="form-label">Код поля формы</label>
            <input type="text" class="form-control" id="code" name="code" value="{{ $field->code }}">
            @error('code')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Тип поля</label>
            <select class="form-select" id="type" disabled>
                <option value="">{{ $field->typeName }}</option>
            </select>
        </div>
        @if($field->canHaveDictionary)
            <div class="mb-3">
                <label for="dictionary_id" class="form-label">Справочник</label>
                <select class="form-select" id="dictionary_id" name="dictionary_id">
                    <option value=""></option>
                    @foreach(\App\Models\Dictionary::all() as $dictionary)
                        <option value="{{ $dictionary->id }}" {{ $field->dictionary_id == $dictionary->id ? 'selected' : '' }}>
                            {{ $dictionary->name }}
                        </option>
                    @endforeach
                </select>
                @error('description')
                <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
        @endif
        <button type="submit" class="btn btn-primary">Обновить</button>
    </form>
@endsection