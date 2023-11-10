@extends('layouts.webapp')
@section('content')
    @php /** @var \App\Models\Field $field */ @endphp
    <h1 class="pt-2 text-center">{{ $form->name }}</h1>
    <form id="webapp-form" enctype="multipart/form-data" type="post" action="{{ route('webapp', $bot) }}">
        @foreach($form->fields()->orderBy('sort', 'asc')->get() as $k => $field)
            @switch($field->type)
                @case('string')
                        <div class="form-group mb-3">
                            <label for="field-{{ $k }}" >{{ $field->name }}</label>
                            <input id="field-{{ $k }}" class="form-control" name="{{ $field->code }}" type="text" value="" placeholder="" {{ $field->required ? 'data-required' : '' }}>
                        </div>
                    @break
                @case('number')
                        <div class="form-group mb-3">
                            <label for="field-{{ $k }}" >{{ $field->name }}</label>
                            <input id="field-{{ $k }}" class="form-control" name="{{ $field->code }}" type="number" value="" placeholder="" {{ $field->required ? 'data-required' : '' }}>
                        </div>
                    @break
                @case('checkbox')
                        <div class="form-check mb-3">
                            <input id="field-{{ $k }}" type="checkbox" class="form-check-input" name="{{ $field->code }}" {{ $field->required ? 'data-required' : '' }}>
                            <label class="form-check-label" for="field-{{ $k }}">{{ $field->name }}</label>
                        </div>
                    @break
                @case('radio')
                    @if($field->dictionary and $field->dictionary->dictionary_values)
                        <div class="form-group mb-3">
                            <label class="form-check-label" for="field-{{ $k }}">{{ $field->name }}</label>
                            @foreach($field->dictionary->dictionary_values as $value)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" id="field-{{ $k }}-{{ $value->id }}" type="radio" name="{{ $field->code }}" value="{{ $value->value }}" {{ $field->required ? 'data-required' : '' }}>
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
                            <textarea class="form-control" id="field-{{ $k }}" rows="7" name="{{ $field->code }}"></textarea>
                        </div>
                    @break
                @case('date')
                        <div class="form-group mb-3">
                            <label for="field-{{ $k }}" >{{ $field->name }}</label>
                            <input id="field-{{ $k }}" class="form-control" type="datetime-local" name="{{ $field->code }}" value="" placeholder="" {{ $field->required ? 'data-required' : '' }}>
                        </div>
                    @break
                @case('select')
                    <div class="form-group mb-3">
                        <label for="field-{{ $k }}" >{{ $field->name }}</label>
                        <select class="form-control" id="field-{{ $k }}" name="{{ $field->code }}" data-select="2" data-live-search="true" {{ $field->required ? 'data-required' : '' }}>
                            @foreach($field->dictionary->dictionary_values as $value)
                                <option value="{{ $value->value }}">{{ $value->value }}</option>
                            @endforeach
                        </select>
                    </div>
                    @break
                @case('tags')
                    <x-tag field="{{ $field->id }}" place="{{ $places->first()->id }}" counter="{{ $k }}"></x-tag>
                    @break
                @case('place')
                    <div class="form-group mb-3">
                        <label for="field-{{ $k }}" >{{ $field->name }}</label>
                        <select class="form-control" id="field-{{ $k }}" name="{{ $field->code }}" data-select="2" data-live-search="true" data-required>
                            @foreach($places as $place)
                                <option value="{{ $place->id }}">{{ $place->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @break
                @case('address')
                    <div class="form-group mb-3">
                        <label for="field-{{ $k }}" >{{ $field->name }}</label>
                        <select class="form-control" id="field-{{ $k }}" name="{{ $field->code }}" data-select="2" data-live-search="true" {{ $field->required ? 'data-required' : '' }}>
                            @foreach($addresses as $address)
                                <option value="{{ $address->id }}" {{ $places->first()->id == $address->id ? 'selected' : '' }}>{{ $address->address }}</option>
                            @endforeach
                        </select>
                    </div>
                    @break
                @case('files')
                    <x-uploader name="{{ $field->name }}"></x-uploader>
                    @break
                @case('price')
                    <x-price isRequired="{{ $field->required }}"  defaultType="{{ $form->default_price_type }}"></x-price>
                    @break
            @endswitch
        @endforeach
        @if($can_select_channels)
            <div class="form-group mb-3">
                <label for="channels" >{{ __('webapp.channels') }}</label>
                <select class="form-control" id="channels" name="channels[]" data-select="2" data-live-search="true" multiple>
                    @foreach($bot->channels as $channel)
                        <option value="{{ $channel->id }}">{{ $channel->name }} ({{ $channel->type }})</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="form-group mb-3">
            <label for="schedule" >{{ __('webapp.publish_date') }}</label>
            <input id="schedule" class="form-control" type="datetime-local" name="schedule[]">
        </div>
        <div class="btn btn-primary mt-2 mb-5" data-role="copy-block"> {{ __('webapp.add_date') }}</div>
            <div class="d-flex d-none justify-content-center align-items-center position-fixed vw-100 vh-100 top-0 bg-light" data-role="spinner">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="sr-only"></span>
                </div>
            </div>
    </form>
@endsection