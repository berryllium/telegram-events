@extends('component.filter')

@section('filters')
    <div class="col-auto">
        <label class="visually-hidden" for="autoSizingInput">Найти</label>
        <input type="text" class="form-control" id="autoSizingInput" name="search" placeholder="Найти">
    </div>
    <div class="col-auto">
        <label class="visually-hidden" for="autoSizingSelect">Preference</label>
        <select class="form-select" id="autoSizingSelect" name="status">
            <option selected>Статус</option>
            @foreach($statuses as $id => $status)
                <option value="{{ $id }}">{{ $status }}</option>
            @endforeach
        </select>
    </div>
@endsection