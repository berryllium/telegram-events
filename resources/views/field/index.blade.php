@extends('base')
@section('title', 'Список полей формы ' . $form->name )
@section('content')

    <a href="{{ route('field.create', $form) }}" class="btn btn-primary mb-4">Создать новое поле формы</a>

    <table class="table table-striped d-block d-md-table overflow-x-auto">
        <tr>
            <th>Название</th>
            <th>Тип</th>
            <th class="action-cell">Действия</th>
        </tr>

        @foreach($fields as $field)
            <tr>
                <td>{{ $field->name }}</td>
                <td>{{ \App\Models\Field::$types[$field->type] }}</td>
                <td class="align-middle text-nowrap">
                    <a href="{{ route('field.edit', [$form, $field]) }}" class="btn btn-primary m-1">
                        <i class="bi bi-pen" role="button"></i>
                    </a>
                    <form action="{{ route('field.destroy', [$form, $field]) }}" method="post" class="d-inline m-1" data-action="delete">
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
        <div class="col-6">{{ $fields->links() }}</div>
    </div>
@endsection