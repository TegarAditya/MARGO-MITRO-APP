@can($viewGate)
    {{-- <a class="btn btn-xs btn-primary" href="{{ route('admin.' . $parent . '.show', $idParent) }}">
        {{ trans('global.view') }}
    </a> --}}
    <a class="px-1" href="{{ route('admin.' . $parent . '.show', $idParent) }}" title="Show">
        <i class="fas fa-eye text-success  fa-lg"></i>
    </a>

@endcan
@can($editGate)
    {{-- <a class="btn btn-xs btn-info" href="{{ route('admin.' . $crudRoutePart . '.edit', $row->id) }}">
        {{ trans('global.edit') }}
    </a> --}}

    <a class="px-1" href="{{ route('admin.' . $crudRoutePart . '.edit', $row->id) }}" title="Edit">
        <i class="fas fa-edit  fa-lg"></i>
    </a>
@endcan
@can($deleteGate)
    <form action="{{ route('admin.' . $crudRoutePart . '.destroy', $row->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        {{-- <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}"> --}}
        <button class="px-1" type="submit" title="delete" style="border: none; background-color:transparent;">
            <i class="fas fa-trash fa-lg text-danger"></i>
        </button>
    </form>
@endcan
