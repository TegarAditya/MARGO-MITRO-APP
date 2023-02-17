@if ($show)
    <a class="px-1" href="{{ route('admin.' . $model . '.show', $row->id) }}" title="Show">
        <i class="fas fa-eye text-success fa-lg"></i>
    </a>
@endif

@if($edit)
    <a class="px-1" href="{{ route('admin.' . $model . '.edit', $row->id) }}" title="Edit">
        <i class="fas fa-edit fa-lg"></i>
    </a>
@endif

@if ($delete)
    <form action="{{ route('admin.' . $model . '.destroy', $row->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button class="px-1" type="submit" title="delete" style="border: none; background-color:transparent;">
            <i class="fas fa-trash fa-lg text-danger"></i>
        </button>
    </form>
@endif

