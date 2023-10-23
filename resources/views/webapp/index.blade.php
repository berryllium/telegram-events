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
                        <div data-role="tags">
                            <div class="form-group mb-1">
                                <label for="tag-{{ $k }}">{{ $field->name }}</label>
                                <textarea class="form-control" id="tag-{{ $k }}" rows="3" name="{{ $field->code }}" {{ $field->required ? 'data-required' : '' }}></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="field-{{ $k }}" >{{ __('webapp.tag_set') }}</label>
                                <select class="form-control" id="field-{{ $k }}" data-select="2" data-live-search="true" multiple>
                                    @foreach($field->dictionary->dictionary_values as $value)
                                        @php
                                            $set = explode(':', $value->value, 2);
                                        @endphp
                                        <option value="{{ trim($set[1]) }}">{{ trim($set[0]) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
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
                                    <option value="{{ $address->id }}">{{ $address->address }}</option>
                                @endforeach
                            </select>
                        </div>
                        @break
                    @case('files')
                        <x-uploader name="{{ $field->name }}"></x-uploader>
                        @break
                    @case('price')
                        <div class="price">
                            <div class="form-group mb-3">
                                <label for="price-{{ $k }}" >{{ __('webapp.price_type') }}</label>
                                <select class="form-control" id="price-{{ $k }}" name="price_type" data-select="2" data-minimum-results-for-search="Infinity">
                                    <option value="min">{{ __('webapp.price_min') }}</option>
                                    <option value="exact">{{ __('webapp.price_exact') }}</option>
                                    <option value="range">{{ __('webapp.price_range') }}</option>
                                </select>
                            </div>
                            <div class="form-group mb-3" data-role="price">
                                <label for="price-{{ $k }}">{{ __('webapp.price') }} <span data-role="from">{{ __('webapp.from') }}</span></label>
                                <input id="field-{{ $k }}" class="form-control" name="{{ $field->code }}" type="number" value="" placeholder="" {{ $field->required ? 'data-required' : '' }}>
                            </div>
                            <div class="form-group mb-3" style="display:none;" data-role="price_to">
                                <label for="price-{{ $k }}">{{ __('webapp.price') }} <span data-role="to">{{ __('webapp.to') }}</label>
                                <input id="field-{{ $k }}" class="form-control" name="{{ $field->code . '_to' }}" type="number" value="" placeholder="">
                            </div>
                        </div>
                        @break
                @endswitch
        @endforeach

        <div class="form-group mb-3">
            <label for="schedule" >{{ __('webapp.publish_date') }}</label>
            <input id="schedule" class="form-control" type="datetime-local" name="schedule[]">
        </div>
        <div class="d-flex d-none justify-content-center align-items-center position-fixed vw-100 vh-100 top-0 bg-light" data-role="spinner">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="sr-only"></span>
            </div>
        </div>
        <div class="btn btn-primary mt-2 mb-5" data-role="copy-block"> {{ __('webapp.add_date') }}</div>
    </form>
@endsection