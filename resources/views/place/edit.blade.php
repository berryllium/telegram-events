@extends('base')
@section('title', __('webapp.places.edit'))
@section('content')
    @if($place->place_files)
        <div class="row mb-4">
            @foreach($place->place_files as $img)
                <div class="col-2">
                    <img src="{{ $img->src }}" alt="{{ $place->name }}" class="mw-100">
                </div>
            @endforeach
        </div>
    @endif
    <form method="post" action="{{ route('place.update', [$place]) }}" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="mb-2">
            <input type="hidden" name="active" value="0">
            <input id="active" type="checkbox" class="form-check-input" name="active" value="1" {{ $place->active ? 'checked' : '' }}>
            <label class="form-check-label" for="active">{{ __('webapp.active') }}</label>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('webapp.title') }}</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $place->name }}">
            @error('name')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="domain" class="form-label">{{ __('webapp.places.domain') }}</label>
            <input type="text" class="form-control" id="domain" name="domain" value="{{ $place->domain }}">
            @error('domain')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">{{ __('webapp.address') }}</label>
            <input type="text" class="form-control" id="address" name="address" value="{{ $place->address }}">
            @error('address')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="address_link" class="form-label">{{ __('webapp.address_link') }}</label>
            <input type="text" class="form-control" id="address_link" name="address_link" value="{{ $place->address_link }}">
            @error('address_link')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="working_hours" class="form-label">{{ __('webapp.working_hours') }}</label>
            <input type="text" class="form-control" id="working_hours" name="working_hours" value="{{ $place->working_hours }}">
            @error('working_hours')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('webapp.email') }}</label>
            <input type="text" class="form-control" id="email" name="email" value="{{ $place->email }}">
            @error('email')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">{{ __('webapp.phone') }}</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ $place->phone }}">
            @error('phone')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">{{ __('webapp.phone') }}</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ $place->phone }}">
            @error('phone')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="link_whatsapp" class="form-label">{{ __('webapp.link_whatsapp') }}</label>
            <input type="text" class="form-control" id="link_whatsapp" name="link_whatsapp" value="{{ $place->link_whatsapp }}">
            @error('link_whatsapp')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="link_tg" class="form-label">{{ __('webapp.link_tg') }}</label>
            <input type="text" class="form-control" id="link_tg" name="link_tg" value="{{ $place->link_tg }}">
            @error('link_tg')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="link_ok" class="form-label">{{ __('webapp.link_ok') }}</label>
            <input type="text" class="form-control" id="link_ok" name="link_ok" value="{{ $place->link_ok }}">
            @error('link_ok')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="link_vk" class="form-label">{{ __('webapp.link_vk') }}</label>
            <input type="text" class="form-control" id="link_vk" name="link_vk" value="{{ $place->link_vk }}">
            @error('link_vk')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="link_instagram" class="form-label">{{ __('webapp.link_instagram') }}</label>
            <input type="text" class="form-control" id="link_instagram" name="link_instagram" value="{{ $place->link_instagram }}">
            @error('link_instagram')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="additional_info" class="form-label">{{ __('webapp.additional_info') }}</label>
            <textarea class="form-control" id="additional_info" name="additional_info" rows="5">{{ $place->additional_info }}</textarea>
            @error('additional_info')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="channels" class="form-label">{{ __('webapp.channels') }}</label>
            <select class="form-select" id="channels" name="channels[]" multiple data-select="2">
                @foreach(\App\Models\Channel::where('telegram_bot_id', session('bot'))->get() as $channel)
                    <option value="{{ $channel->id }}" {{ $place->channels->contains($channel->id) ? 'selected' : ''}}>
                        {{ $channel->name }}
                    </option>
                @endforeach
            </select>
            @error('form')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="tag_set" class="form-label">{{ __('webapp.tag_set') }}</label>
            <select class="form-select" id="tag_set" name="tag_set" data-select="2">
                <option value=""></option>
                @foreach(\App\Models\Dictionary::where('telegram_bot_id', session('bot'))->get() as $tag_set)
                    <option value="{{ $tag_set->id }}" {{ $place->tag_set == $tag_set->id ? 'selected' : ''}}>
                        {{ $tag_set->name }}
                    </option>
                @endforeach
            </select>
            @error('tag_set')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">{{ __('webapp.description') }}</label>
            <textarea class="form-control" id="description" name="description" rows="5">{{ $place->description }}</textarea>
            @error('description')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <a href="{{ route('slider.create', ['place' => $place, 'type' => 'horizontal']) }}">{{ __('webapp.slider_horizontal') }}</a>
{{--            <a href="{{ route('slider.create', ['place' => $place, 'type' => 'vertical']) }}">{{ __('webapp.slider_vertical') }}</a>--}}
        </div>
        <button type="submit" class="btn btn-primary">{{ __('webapp.update') }}</button>
    </form>
@endsection