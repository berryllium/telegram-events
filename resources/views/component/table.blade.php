<table class="table table-striped d-block d-md-table overflow-x-auto">
    <tr>
        @foreach($headers as $header)
            <th>{{ $header }}</th>
        @endforeach
        <th class="action-cell">{{ __('webapp.actions') }}</th>
    </tr>

    @foreach($entities['value'] as $entity)
        <tr>
            @foreach($fields as $field)
                @if($entity->$field instanceof \Illuminate\Database\Eloquent\Model)
                    <td><a href="{{ route( Str::singular($entity->$field->getTable()) . '.edit', $entity->$field) }}">{{ $entity->$field->name }}</a></td>
                @else
                    <td>{{ $entity->$field }}</td>
                @endif
            @endforeach
            <td class="align-middle text-nowrap">
                @if($entities['name'] == 'form')
                    <form action="{{ route($entities['name'] . '.copy', $entity)  }}" method="post" class="d-inline m-1">
                        @csrf
                        <button class="btn btn-warning" type="submit">
                            <i class="bi bi-copy" role="button" onclick="this.parentNode.submit()"></i>
                        </button>
                    </form>
                @endif
                <a href="{{ route($entities['name'] . '.edit', $entity) }}" class="btn btn-primary m-1">
                    <i class="bi bi-pen" role="button"></i>
                </a>
                <form action="{{ route($entities['name'] . '.destroy', $entity) }}" method="post" class="d-inline m-1" data-action="delete" data-text="{{ __('webapp.deletion_confirm') }}">
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
@if($entities['value'] instanceof LengthAwarePaginator)
    <div class="row">
        <div class="col-6">{{ $entities['value']->links() }}</div>
    </div>
@endif