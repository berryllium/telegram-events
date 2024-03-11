@extends('base')
@section('title', __("webapp.slider_$type"))
@section('content')
    <form enctype="multipart/form-data" method="post" action="{{ route('slider.store', ['type' => $type, 'place' => $place]) }}">
        @csrf
        <input type="hidden" name="type" value="{{ $type }}">
        <h3>{{ __('webapp.slides') }} </h3>
        <div class="slides row">
            <div class="col-12 mb-3 d-flex flex-column flex-lg-row border border-1" data-role="block">
                <input type="text" name="name[]" class="form-control mb-1" placeholder="{{ __('webapp.title') }}" aria-label="Username">
                <input type="text" name="link[]" class="form-control mb-1" placeholder="{{ __('webapp.link') }}" aria-label="Username">
                <input type="file" name="files[]" class="form-control mb-1">
                <button class="btn btn-danger" onclick="$(this).parent().remove()">{{ __('webapp.delete') }}</button>
            </div>
        </div>
        <div class="btn btn-primary" data-role="copy-block">+</div>
        <div class="row mt-5">
            <div class="col-3">
                <button class="btn btn-primary">{{ __('webapp.create') }}</button>
            </div>
        </div>
    </form>
@endsection