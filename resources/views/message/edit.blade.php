@extends('base')
@section('title', 'Редактирование сообщения от ' . $msg->author->name)
@section('content')
    <form method="post" action="{{ route('message.update', $msg) }}">
        @csrf
        @method('put')
        <div class="mb-3">
            <label for="text" class="form-label">Текст сообщения</label>
            <textarea class="form-control d-none" id="text" name="text" rows="5" data-editor="ck">{{ $msg->htmlText }}</textarea>
            @error('text')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Обновить</button>
    </form>

    <div class="row">
        <div class="col-12"><h3 class="mt-4">Файлы</h3></div>
        <div class="col-12 d-flex align-middle">
            @foreach($msg->message_files as $file)
                <div class="col-2 pe-2 pb-2">
                    <div class="border p-3">
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
                        <a href="#" class="d-block text-sm-center"><i class="bi bi-trash"></i><span>Удалить</span></a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="col-12">
            <div class="form-group mb-3 mt-2">
                <label><input id="files" class="form-control" name="files[]" type="file" multiple></label>
            </div>
        </div>
    </div>

    <h3 class="mt-4">Расписание отправки</h3>
    @include('message.schedule', ['schedules' => $msg->message_schedules()->with('telegram_channels')->get()])
@endsection