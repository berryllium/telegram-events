@extends('base')
@section('title', 'Список веб-форм')
@section('content')

    <a href="{{ route('form.create') }}" class="btn btn-primary mb-4">Создать новую форму</a>

    <table class="table table-striped">
        <tr>
            <th>Название</th>
            <th>Описание</th>
            <th>Действия</th>
        </tr>
        @foreach($forms as $form)
            <tr>
                <td>{{ $form->name }}</td>
                <td>{{ $form->description }}</td>
                <td class="align-middle text-nowrap">
                    <a href="{{ route('form.edit', $form) }}" class="btn btn-primary m-1">
                        <i class="bi bi-pen" role="button"></i>
                    </a>
                    <form action="{{ route('form.destroy', $form->id) }}" method="post" class="d-inline m-1">
                        @csrf
                        @method('delete')
                        <button class="btn btn-danger" type="submit">
                            <i class="bi bi-trash" role="button" onclick="this.parentNode.submit()"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
    <div class="row">
        <div class="col-6">{{ $forms->links() }}</div>
    </div>
@endsection