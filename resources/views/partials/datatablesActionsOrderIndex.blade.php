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
