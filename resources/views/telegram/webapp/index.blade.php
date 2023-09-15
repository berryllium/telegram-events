@extends('layouts.webapp')
@section('content')
    <h1 class="pt-2 text-center">{{ $form->name }}</h1>
    <form id="webapp-form" type="post" action="{{ route('webapp', $bot) }}">
        @foreach($form->fields as $k => $field)
                @switch($field->type)
                    @case('string')
                            <div class="form-group mb-3">
                                <label for="field-{{ $k }}" >{{ $field->name }}</label>
                                <input id="field-{{ $k }}" class="form-control" name="{{ $field->code }}" type="text" value="" placeholder="">
                            </div>
                        @break
                    @case('number')
                            <div class="form-group mb-3">
                                <label for="field-{{ $k }}" >{{ $field->name }}</label>
                                <input id="field-{{ $k }}" class="form-control" name="{{ $field->code }}" type="number" value="" placeholder="">
                            </div>
                        @break
                    @case('checkbox')
                            <div class="form-check mb-3">
                                <input id="field-{{ $k }}" type="checkbox" class="form-check-input" name="{{ $field->code }}">
                                <label class="form-check-label" for="field-{{ $k }}">{{ $field->name }}</label>
                            </div>
                        @break
                    @case('radio')
                        <div class="form-check mb-3">
                            <input id="field-{{ $k }}" type="radio" class="form-check-input" name="{{ $field->code }}">
                            <label class="form-check-label" for="field-{{ $k }}">{{ $field->name }}</label>
                        </div>
                        @break
                    @case('text')
                            <div class="form-group mb-3">
                                <label for="field-{{ $k }}">{{ $field->name }}</label>
                                <textarea class="form-control" id="field-{{ $k }}" rows="3" name="{{ $field->code }}"></textarea>
                            </div>
                        @break
                    @case('date')
                            <div class="form-group mb-3">
                                <label for="field-{{ $k }}" >{{ $field->name }}</label>
                                <input id="field-{{ $k }}" class="form-control" type="datetime-local" name="{{ $field->code }}" value="" placeholder="">
                            </div>
                        @break
                    @case('select')
                        <div class="form-group mb-3">
                            <label for="field-{{ $k }}" >{{ $field->name }}</label>
                            <select class="form-control" id="field-{{ $k }}" name="{{ $field->code }}" data-select="2" data-live-search="true">
                                <option value="1">1</option>
                                <option value="2">2</option>
                            </select>
                        </div>
                        @break
                    @case('place')
                        <div class="form-group mb-3">
                            <label for="field-{{ $k }}" >{{ $field->name }}</label>
                            <select class="form-control" id="field-{{ $k }}" name="{{ $field->code }}" data-select="2" data-live-search="true">
                                @foreach($form->places as $place)
                                    <option value="{{ $place->id }}">{{ $place->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @break
                    @case('address')
                        <div class="form-group mb-3">
                            <label for="field-{{ $k }}" >{{ $field->name }}</label>
                            <select class="form-control" id="field-{{ $k }}" name="{{ $field->code }}" data-select="2" data-live-search="true">
                                @foreach($form->places as $place)
                                    <option value="{{ $place->id }}">{{ $place->address }}</option>
                                @endforeach
                            </select>
                        </div>
                        @break
                @endswitch
        @endforeach

        <div class="form-group mb-3">
            <label for="schedule" >Время публикации поста</label>
            <input id="schedule" class="form-control" type="datetime-local" name="schedule[]">
        </div>

    </form>
@endsection