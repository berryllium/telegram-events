@extends('base')
@section('title', __('webapp.messages.edit', ['author' => $msg->author?->name]) )
@section('content')
    <form method="post" class="row" action="{{ route('message.update', $msg) }}" enctype="multipart/form-data">
        @csrf
        @method('put')

        <div class="mb-3 col-lg-6">
            <div class="col-12"><h3 class="mt-4">{{ __('webapp.message_text') }}</h3></div>
            <label for="text" class="d-none"></label>
            <div class="text">
                <div class="original">
                    <div class="text-old border border-1">{!! $msg->htmlText !!}</div>
                    <button class="btn btn-warning mt-1 mb-5" type="button" onclick="$('.editor').removeClass('d-none').find('textarea').attr('name', 'text');$('.original').hide()">
                        Изменить текст сообщения
                    </button>
                </div>
                <div class="editor d-none">
                    <textarea class="form-control" id="text" rows="5" data-editor="ck">
                        {{ $msg->htmlText }}
                    </textarea>
                </div>
            </div>
            @error('text')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
            <div class="form-check mt-2">
                <input name="allowed" class="form-check-input" type="checkbox" value="1" id="flexCheckAllowed" {{ $msg->allowed ? 'checked' : '' }}>
                <label class="form-check-label" for="flexCheckAllowed">
                    {{ __('webapp.messages.sending_allowed') }}
                </label>
            </div>
        </div>

        <div class="col-lg-6">
            <div><h3 class="mt-4">{{ __('webapp.files') }}</h3></div>
            @if($msg->message_files->count())
                <div class="d-flex align-middle flex-wrap">
                    @foreach($msg->message_files as $file)
                        <div class="file col-3 pe-1 pb-1 align-self-end">
                            <div class="border p-2">
                                <a href="<?=$file->src?>" class="d-block mb-3" target="_blank">
                                    <input type="hidden" name="current_files[]" value="{{ $file->id }}">
                                    @if($file->type == 'video')
                                        <video width="100%" height="auto" controls>
                                            <source src="{{ $file->src }}" type="{{ $file->mime }}">
                                            Your browser does not support the video tag.
                                        </video>
                                    @else
                                        <img src="<?=$file->src?>" alt="{{ $file->filename }}" height="auto" width="100%">
                                    @endif
                                </a>
                                <a href="javascript:void(0)" onclick="this.closest('.file').remove()" class="d-block text-sm-center"><i class="bi bi-trash"></i><span>{{ __('webapp.delete') }}</span></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            @error('files.*')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror

            <div>
                <div class="form-group mb-3 mt-2">
                    <label><input id="files" class="form-control" name="files[]" type="file" multiple></label>
                </div>
            </div>
        </div>

        @if(!$msg->trashed())
        <div>
            <button type="submit" class="btn btn-primary">{{ __('webapp.update') }}</button>
        </div>
        @endif
    </form>

    <h3 class="mt-4">{{ __('webapp.messages.schedule') }}</h3>
    @include('message.schedule', ['schedules' => $msg->message_schedules()->with('channels')->get()])
@endsection