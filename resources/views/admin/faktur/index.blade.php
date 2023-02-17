@extends('layouts.admin')
@section('content')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="{{ route('admin.faktur.history') }}">
            History Pengiriman Buku
        </a>
    </div>
</div>
<div class="card">
    <div class="card-header">
        Daftar Pengiriman Buku
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Invoice">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        No Surat jalan
                    </th>
                    {{-- <th>
                        {{ trans('cruds.invoice.fields.no_invoice') }}
                    </th> --}}
                    <th>
                        Tanggal Input
                    </th>
                    <th>
                        Sales
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
        ajax: "{{ route('admin.faktur.index') }}",
        columns: [{
                data: 'placeholder',
                name: 'placeholder'
            },
            {
                data: 'no_suratjalan',
                name: 'no_suratjalan',
                class: 'text-center'
            },
            // { data: 'no_invoice', name: 'no_invoice', class: 'text-center' },
            {
                data: 'date',
                name: 'date',
                class: 'text-center'
            },
            {
                data: 'sales',
                name: 'sales',
                class: 'text-center'
            },
            {
                data: 'actions',
                name: '{{ trans('global.actions ') }}',class: 'text-center'
            }
        ],
        orderCellsTop: true,
        order: [
            [1, 'desc']
        ],
        pageLength: 25,
    };
    let table = $('.datatable-Invoice').DataTable(dtOverrideGlobals);
    $('a[data-toggle="tab"]').on('shown.bs.tab click', function (e) {
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });
});
</script>
@endsection
