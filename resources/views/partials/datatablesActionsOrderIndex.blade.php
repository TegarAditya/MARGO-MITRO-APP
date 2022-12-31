@can($viewGate)
    <a class="px-1" href="{{ route('admin.' . $crudRoutePart . '.show', $row->id) }}" title="Show">
        <i class="fas fa-eye text-success  fa-lg"></i>
    </a>
@endcan
@can($editGate)
    <a class="px-1" href="{{ route('admin.' . $crudRoutePart . '.edit', $row->id) }}" title="Edit">
        <i class="fas fa-edit  fa-lg"></i>
    </a>
@endcan
<a class="px-1" href="{{ route('admin.' . $crudRoutePart . '.estimasi', $row->id) }}" title="Cetak Estimasi">
    <i class="fas fa-print text-secondary  fa-lg"></i>
</a>
<a class="px-1" href="{{ route('admin.' . $crudRoutePart . '.saldo', $row->id) }}" title="Cetak Saldo">
    <i class="fas fa-money text-warning  fa-lg"></i>
</a>
<a class="px-1" href="{{ route('admin.' . $crudRoutePart . '.saldo_rekap', $row->id) }}" title="Cetak Rekap Saldo">
    <i class="fas fa-money text-danger  fa-lg"></i>
</a>
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
