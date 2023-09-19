@extends('base')
@section('title', 'Редактирование сообщения от ' . $msg->author->name)
@section('content')
    <form method="post" action="{{ route('message.update', $msg) }}">
        @csrf
        @method('put')
        <div class="mb-3">
            <label for="text" class="form-label">Текст сообщения</label>
            <textarea class="form-control" id="text" name="text" rows="5">{{ $msg->text }}</textarea>
            @error('text')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Обновить</button>
    </form>
    <h3 class="mt-4">Файлы</h3>
    <div>
        @foreach($msg->message_files as $file)
            <a href="<?=$file->src?>" target="_blank">
                <img src="<?=$file->src?>" alt="$file->filename" width="100px" height="auto">
            </a>
        @endforeach
    </div>
    <h3 class="mt-4">Расписание отправки</h3>
    @include('message.schedule', ['schedules' => $msg->message_schedules()->with('telegram_channels')->get()])
@endsection