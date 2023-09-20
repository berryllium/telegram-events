<a href="{{ route($model . '.edit', $entity) }}" class="btn btn-primary m-1">
    <i class="bi bi-pen" role="button"></i>
</a>
<form action="{{ route($model . '.destroy', $entity) }}" method="post" class="d-inline m-1" data-action="delete">
    @csrf
    @method('delete')
    <button class="btn btn-danger" type="submit">
        <i class="bi bi-trash" role="button" onclick="this.parentNode.submit()"></i>
    </button>
</form>