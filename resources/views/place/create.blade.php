@extends('base')
@section('title', __('webapp.places.add'))
@section('content')
    <form method="post" action="{{ route('place.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="formFile" class="form-label">{{ __('webapp.logo') }}</label>
            <input class="form-control" type="file" id="formFile" name="logo_image">
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('webapp.title') }}</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
            @error('name')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-2">
            <input type="hidden" name="show_chat_icon" value="0">
            <input id="show_chat_icon" type="checkbox" class="form-check-input" name="show_chat_icon" value="1" {{ old('show_chat_icon') ? 'checked' : '' }}>
            <label class="form-check-label" for="show_chat_icon">{{ __('webapp.show_chat_icon') }}</label>
        </div>
        <div class="mb-3">
            <label for="template" class="form-label">{{ __('webapp.title') }}</label>
            <input type="text" class="form-control" id="template" name="template" value="{{ old('template') }}">
            @error('template')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="domain" class="form-label">{{ __('webapp.places.domain') }}</label>
            <input type="text" class="form-control" id="domain" name="domain" value="{{ old('domain') }}">
            @error('domain')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="template" class="form-label">{{ __('webapp.template') }}</label>
            <select class="form-select" id="template" name="template">
                <option value="default" selected>По умолчанию (default)</option>
                <option value="svetofor">Светофор (svetofor)</option>
            </select>
            @error('form')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">{{ __('webapp.address') }}</label>
            <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}">
            @error('address')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="address_link" class="form-label">{{ __('webapp.address_link') }}</label>
            <input type="text" class="form-control" id="address_link" name="address_link" value="{{ old('address_link') }}">
            @error('address_link')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="working_hours" class="form-label">{{ __('webapp.working_hours') }}</label>
            <input type="text" class="form-control" id="working_hours" name="working_hours" value="{{ old('working_hours') }}">
            @error('working_hours')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('webapp.email') }}</label>
            <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}">
            @error('email')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">{{ __('webapp.phone') }}</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
            @error('phone')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="link_whatsapp" class="form-label">{{ __('webapp.link_whatsapp') }}</label>
            <input type="text" class="form-control" id="link_whatsapp" name="link_whatsapp" value="{{ old('link_whatsapp') }}">
            @error('link_whatsapp')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="link_tg" class="form-label">{{ __('webapp.link_tg') }}</label>
            <input type="text" class="form-control" id="link_tg" name="link_tg" value="{{ old('link_tg') }}">
            @error('link_tg')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="link_ok" class="form-label">{{ __('webapp.link_ok') }}</label>
            <input type="text" class="form-control" id="link_ok" name="link_ok" value="{{ old('link_ok') }}">
            @error('link_ok')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="link_vk" class="form-label">{{ __('webapp.link_vk') }}</label>
            <input type="text" class="form-control" id="link_vk" name="link_vk" value="{{ old('link_vk') }}">
            @error('link_vk')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="link_instagram" class="form-label">{{ __('webapp.link_instagram') }}</label>
            <input type="text" class="form-control" id="link_instagram" name="link_instagram" value="{{ old('link_instagram') }}">
            @error('link_instagram')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="additional_info" class="form-label">{{ __('webapp.additional_info') }}</label>
            <textarea class="form-control" id="additional_info" name="additional_info" rows="5">{{ old('additional_info') }}</textarea>
            @error('additional_info')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="channels" class="form-label">{{ __('webapp.channels') }}</label>
            <select class="form-select" id="channels" name="channels[]" multiple data-select="2">
                @foreach(\App\Models\Channel::where('telegram_bot_id', session('bot'))->get() as $channel)
                    <option value="{{ $channel->id }}" {{ old('channels') && in_array($channel->id, old('channels')) ? 'selected' : ''}}>
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
            <select class="form-select" id="tag_set" name="channels[]">
                <option value=""></option>
                @foreach(\App\Models\Dictionary::where('telegram_bot_id', session('bot'))->get() as $tag_set)
                    <option value="{{ $tag_set->id }}" {{ old('tag_set') == $tag_set->id ? 'selected' : ''}}>
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
            <textarea class="form-control" id="description" name="description" rows="5">{{ old('description') }}</textarea>
            @error('description')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">{{ __('webapp.image') }}</label>
            <input class="form-control" type="file" id="image" name="image">
        </div>
        <div class="mb-3">
            <label for="appeal_text" class="form-label">{{ __('webapp.appeal_text') }}</label>
            <textarea class="form-control" id="appeal_text" name="appeal_text" rows="5">{{ old('appeal_text') }}</textarea>
            @error('appeal_text')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="appeal_image" class="form-label">{{ __('webapp.appeal_image') }}</label>
            <input class="form-control" type="file" id="appeal_image" name="appeal_image">
        </div>
        <div class="mb-3">
            <label for="seo_title" class="form-label">{{ __('webapp.seo_title') }}</label>
            <input type="text" class="form-control" id="seo_title" name="seo_title" value="{{ old('seo_title') }}">
            @error('seo_title')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="seo_description" class="form-label">{{ __('webapp.seo_description') }}</label>
            <input type="text" class="form-control" id="seo_description" name="seo_description" value="{{ old('seo_description') }}">
            @error('seo_description')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="header_script" class="form-label">{{ __('webapp.header_script') }}</label>
            <textarea class="form-control" id="header_script" name="header_script" rows="5">{{ old('header_script') }}</textarea>
            @error('header_script')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">{{ __('webapp.add') }}</button>
    </form>
@endsection