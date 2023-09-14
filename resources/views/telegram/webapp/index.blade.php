@extends('layouts.webapp')
@section('content')
    <h1 class="pt-2 text-center">{{ $form->name }}</h1>
    <form action="">
        @foreach($form->fields as $k => $field)
                @switch($field->type)
                    @case('string')
                            <div class="form-group mb-3">
                                <label for="field-{{ $k }}" >{{ $field->name }}</label>
                                <input id="field-{{ $k }}" class="form-control" type="text" value="" placeholder="">
                            </div>
                        @break
                    @case('number')
                            <div class="form-group mb-3">
                                <label for="field-{{ $k }}" >{{ $field->name }}</label>
                                <input id="field-{{ $k }}" class="form-control" type="number" value="" placeholder="">
                            </div>
                        @break
                    @case('checkbox')
                            <div class="form-check mb-3">
                                <input id="field-{{ $k }}" type="checkbox" class="form-check-input">
                                <label class="form-check-label" for="field-{{ $k }}">{{ $field->name }}</label>
                            </div>
                        @break
                    @case('radio')
                        <div class="form-check mb-3">
                            <input id="field-{{ $k }}" type="radio" class="form-check-input">
                            <label class="form-check-label" for="field-{{ $k }}">{{ $field->name }}</label>
                        </div>
                        @break
                    @case('text')
                            <div class="form-group mb-3">
                                <label for="field-{{ $k }}">{{ $field->name }}</label>
                                <textarea class="form-control" id="field-{{ $k }}" rows="3"></textarea>
                            </div>
                        @break
                    @case('date')
                            <div class="form-group mb-3">
                                <label for="field-{{ $k }}" >{{ $field->name }}</label>
                                <input id="field-{{ $k }}" class="form-control" type="datetime-local" value="" placeholder="">
                            </div>
                        @break
                    @case('select')
                        <div class="form-group mb-3">
                            <label for="field-{{ $k }}" >{{ $field->name }}</label>
                            <select class="form-control" id="field-{{ $k }}" data-select="2" data-live-search="true">
                                <option value="1">1</option>
                                <option value="2">2</option>
                            </select>
                        </div>
                        @break
                    @case('place')
                        <div class="form-group mb-3">
                            <label for="field-{{ $k }}" >{{ $field->name }}</label>
                            <select class="form-control" id="field-{{ $k }}" data-select="2" data-live-search="true">
                                @foreach($form->places as $place)
                                    <option value="{{ $place->id }}">{{ $place->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @break
                    @case('address')
                        <div class="form-group mb-3">
                            <label for="field-{{ $k }}" >{{ $field->name }}</label>
                            <select class="form-control" id="field-{{ $k }}" data-select="2" data-live-search="true">
                                @foreach($form->places as $place)
                                    <option value="{{ $place->id }}">{{ $place->address }}</option>
                                @endforeach
                            </select>
                        </div>
                        @break
                @endswitch
        @endforeach
        <div class="buttons">
            <button class="btn btn-primary w-100">Отправить</button>
        </div>
    </form>
@endsection