<table class="table table-striped d-block d-md-table overflow-x-auto">
    <tr>
        @foreach($headers as $header)
            <th>{{ $header }}</th>
        @endforeach
        <th class="action-cell">Действия</th>
    </tr>

    @foreach($entities['value'] as $entity)
        <tr>
            @foreach($fields as $field)
                @if(is_object($entity->$field))
                    <td><a href="{{ route( Str::singular($entity->$field->getTable()) . '.edit', $entity->$field) }}">{{ $entity->$field->name }}</a></td>
                @else
                    <td>{{ $entity->$field }}</td>
                @endif
            @endforeach
            <td class="align-middle text-nowrap">
                <a href="{{ route($entities['name'] . '.edit', $entity) }}" class="btn btn-primary m-1">
                    <i class="bi bi-pen" role="button"></i>
                </a>
                <form action="{{ route($entities['name'] . '.destroy', $entity) }}" method="post" class="d-inline m-1" data-action="delete">
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
    <div class="col-6">{{ $entities['value']->links() }}</div>
</div>