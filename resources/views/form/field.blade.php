<table class="table table-striped d-block d-md-table overflow-x-auto">
    <tr>
        <th>Название</th>
        <th>Тип</th>
        <th>Код (для вставки в шаблон)</th>
        <th class="action-cell">Действия</th>
    </tr>

    @foreach($form->fields as $field)
        <tr>
            <td>{{ $field->name }}</td>
            <td>{{ \App\Models\Field::$types[$field->type] }}</td>
            <td>&lcub;&lcub;&nbsp;${{ $field->code }}&nbsp;&rcub;&rcub;</td>
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