@extends('base')
@section('title', __('webapp.authors.list'))
@section('content')

    <table class="table table-striped d-block d-md-table overflow-x-auto">
        <tr>
            <th>{{ __('webapp.author') }}</th>
            <th class="action-cell">{{ __('webapp.name') }}</th>
            <th class="action-cell">{{ __('webapp.description') }}</th>
            <th class="action-cell">{{ __('webapp.actions') }}</th>
        </tr>

        @foreach($pivots as $pivot)
            <tr>
                <td>{{ $pivot->author->name }}</td>
                <td>{{ $pivot->title }}</td>
                <td>{{ $pivot->description }}</td>
                <td class="align-middle text-nowrap">
                    @if(auth()->user()->hasAnyRole('supervisor', 'admin'))
                        <form action="{{ route('author.destroy', $pivot->author) }}" method="post" class="d-inline m-1" data-action="delete">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger" type="submit">
                                <i class="bi bi-trash" role="button" onclick="this.parentNode.submit()"></i>
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('author.edit', $pivot->author) }}" class="btn btn-primary m-1">
                        <i class="bi bi-pen" role="button"></i>
                    </a>
                </td>
            </tr>
        @endforeach
    </table>
    <div class="row"><div class="col-6">{{ $pivots->links() }}</div></div>

@endsection