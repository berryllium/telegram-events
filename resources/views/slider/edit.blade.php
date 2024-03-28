@extends('base')
@section('title', __("webapp.slider_$type"))
@section('content')
    <form method="post" enctype="multipart/form-data" action="{{ route('slider.update', ['type' => $type, 'place' => $place, 'slider' => $slider]) }}">
        @csrf
        @method('put')
        <input type="hidden" name="type" value="{{ $type }}">
        <h3>{{ __('webapp.slides') }} </h3>
        <div class="slides container">
            @foreach($slider->slides as $slide)
                <div class="row block bg-light border border-1 mb-4 p-2">
                    <div class="col-12 col-lg-6">
                        <img src="{{ $slide->src }}" alt="" class="w-100">
                    </div>
                    <div class="col-12 col-lg-6 mb-3">
                        <input type="hidden" name="old_id[{{ $slide->id }}]" value="{{ $slide->id }}">
                        <input type="text" name="old_name[{{ $slide->id }}]" class="form-control mb-1" placeholder="{{ __('webapp.title') }}" aria-label="Username" value="{{ $slide->name }}">
                        <input type="text" name="old_link[{{ $slide->id }}]" class="form-control mb-1" placeholder="{{ __('webapp.link') }}" aria-label="Username" value="{{ $slide->link }}">
                        <input type="file" name="old_files[{{ $slide->id }}]" class="form-control mb-1">
                        <button class="btn btn-danger" onclick="$(this).closest('.block').remove()">{{ __('webapp.delete') }}</button>
                    </div>
                </div>
            @endforeach
            <div class="slides row">
                <div class="col-12 mb-3 d-flex flex-column flex-lg-row" data-role="block">
                    <input type="text" name="name[]" class="form-control mb-1" placeholder="{{ __('webapp.title') }}" aria-label="Username">
                    <input type="text" name="link[]" class="form-control mb-1" placeholder="{{ __('webapp.link') }}" aria-label="Username">
                    <input type="file" name="files[]" class="form-control mb-1">
                    <button class="btn btn-danger" onclick="$(this).parent().remove()">{{ __('webapp.delete') }}</button>
                </div>
            </div>
            <div class="btn btn-primary" data-role="copy-block">+</div>
        </div>
        <div class="row mt-5">
            <div class="col-3">
                <button class="btn btn-primary">{{ __('webapp.update') }}</button>
            </div>
        </div>
    </form>
@endsection