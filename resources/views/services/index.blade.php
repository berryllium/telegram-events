@extends('base')
@section('title', __("webapp.services") . ' - ' . $place->name)
@section('content')
    <form method="post" enctype="multipart/form-data" action="{{ route('services.save', ['place' => $place]) }}">
        @csrf
        <div class="slides container">
            @foreach($place->services as $service)
                <div class="row block bg-light border border-1 mb-4 p-2">
                    @if($service->image)
                    <div class="col-12">
                        <img src="{{ $service->src }}" alt="" class="w-100">
                    </div>
                    @endif
                    <div class="col-12 mb-3">
                        <input type="hidden" name="old_id[{{ $service->id }}]" value="{{ $service->id }}">
                        <input type="text" name="old_name[{{ $service->id }}]" class="form-control mb-1" placeholder="{{ __('webapp.title') }}" aria-label="Username" value="{{ $service->name }}">
                        <textarea name="old_description[{{ $service->id }}]" class="form-control mb-1" placeholder="{{ __('webapp.description') }}" aria-label="Username">{{ $service->description }}</textarea>
                        <input type="file" name="old_images[{{ $service->id }}]" class="form-control mb-1" value="{{ $service->image }}">
                    </div>
                    <div class="col-1"><button class="btn btn-danger" onclick="$(this).closest('.block').remove()">{{ __('webapp.delete') }}</button></div>
                </div>
            @endforeach
            <div class="slides row mb-5">
                <div class="col-12 col-lg-5">
                    <input type="text" name="name[]" class="form-control mb-1" placeholder="{{ __('webapp.title') }}" aria-label="Username">
                    <input type="file" name="images[]" class="form-control mb-1">
                </div>
                <div class="col-12 col-lg-6">
                    <textarea name="description[]" class="form-control mb-1" aria-label="description"></textarea>
                </div>
                <div class="col-12 col-lg-1">
                    <button class="btn btn-danger" onclick="$(this).parent().remove()">{{ __('webapp.delete') }}</button>
                </div>
            </div>
            <div class="btn btn-primary" data-role="copy-block">+</div>
        </div>
        <div class="row mt-5">
            <div class="col-3">
                <button class="btn btn-primary">{{ __('webapp.save') }}</button>
            </div>
        </div>
    </form>
@endsection