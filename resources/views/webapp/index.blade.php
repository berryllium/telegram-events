@extends('layouts.webapp')
@section('content')
    @php /** @var \App\Models\Field $field */ @endphp
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
                        @if($field->dictionary and $field->dictionary->dictionary_values)
                            <div class="form-group mb-3">
                                <label class="form-check-label" for="field-{{ $k }}">{{ $field->name }}</label>
                                @foreach($field->dictionary->dictionary_values as $value)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" id="field-{{ $k }}-{{ $value->id }}" type="radio" name="{{ $field->code }}" value="{{ $value->value }}">
                                        <label class="form-check-label" for="field-{{ $k }}-{{ $value->id }}">
                                            {{ $value->value }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @endif
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
                                @foreach($field->dictionary->dictionary_values as $value)
                                    <option value="{{ $value->value }}">{{ $value->value }}</option>
                                @endforeach
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
                    @case('files')
                        <div class="form-group mb-3">
                            <label for="field-{{ $k }}" class="form-label">{{ $field->name }}</label>
                            <input id="field-{{ $k }}" class="form-control" name="files[]" type="file" multiple>
                        </div>
                        @break
                @endswitch
        @endforeach

        <div class="form-group mb-3">
            <label for="schedule" >{{ __('webapp.publish_date') }}</label>
            <input id="schedule" class="form-control" type="datetime-local" name="schedule[]">
        </div>
        <div data-role="copy-block" class="mb-5">
            <div class="btn btn-primary"> {{ __('webapp.add_date') }}</div>
        </div>
    </form>
@endsection