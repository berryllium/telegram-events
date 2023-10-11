<table class="table table-striped d-block d-md-table overflow-x-auto">
    <tr>
        <th>{{ __('webapp.name') }}</th>
        <th>{{ __('webapp.type') }}</th>
        <th>{{ __('webapp.template_code') }}</th>
        <th class="action-cell">{{ __('webapp.actions') }}</th>
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
                @if($field->code !='place')
                    <form action="{{ route('field.destroy', [$form, $field]) }}" method="post" class="d-inline m-1" data-action="delete">
                        @csrf
                        @method('delete')
                        <button class="btn btn-danger" type="submit">
                            <i class="bi bi-trash" role="button" onclick="this.parentNode.submit()"></i>
                        </button>
                    </form>
                @endif
            </td>
        </tr>
    @endforeach
</table>