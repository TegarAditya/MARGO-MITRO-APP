@extends('layouts.admin')
@section('content')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="{{ route('admin.purchases.create') }}">
            Tambah Produk Masuk
        </a>
    </div>
</div>
<div class="card">
    <div class="card-header">
        Daftar Produk Masuk
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Purchase">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        No Produk Masuk
                    </th>
                    <th>
                        Tanggal
                    </th>
                    <th>
                        Subkontraktor
                    </th>
                    <th>
                        Catatan
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
$(function () {
    let dtOverrideGlobals = {
        processing: true,
        serverSide: true,
        retrieve: true,
        aaSorting: [],
        ajax: "{{ route('admin.purchases.index') }}",
        columns: [{
                data: 'placeholder',
                name: 'placeholder'
            },
            {
                data: 'no_spk',
                name: 'no_spk',
                class: 'text-center'
            },
            {
                data: 'date',
                name: 'date',
                class: 'text-center'
            },
            {
                data: 'subkontraktor',
                name: 'subkontraktor',
                class: 'text-center'
            },
            {
                data: 'note',
                name: 'note'
            },
            {
                data: 'actions',
                name: '{{ trans('global.actions ') }}',
                class: 'text-center'
            }
        ],
        orderCellsTop: true,
        order: [
            [1, 'desc']
        ],
        pageLength: 25,
    };
    let table = $('.datatable-Purchase').DataTable(dtOverrideGlobals);
    $('a[data-toggle="tab"]').on('shown.bs.tab click', function (e) {
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });
});

</script>
@endsection
