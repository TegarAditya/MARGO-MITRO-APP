@extends('layouts.admin')
@section('content')
@can('stock_adjustment_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.stock-adjustments.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.stockAdjustment.title_singular') }}
            </a>
            {{-- <button class="btn btn-primary" data-toggle="modal" data-target="#importModal">
                Import
            </button> --}}
            {{-- @include('csvImport.import_modal', ['model' => 'StockAdjustment', 'route' => 'admin.stock-adjustments.import']) --}}
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.stockAdjustment.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-StockAdjustment">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.stockAdjustment.fields.date') }}
                    </th>
                    <th>
                        {{ trans('cruds.stockAdjustment.fields.operation') }}
                    </th>
                    <th>
                        {{ trans('cruds.stockAdjustment.fields.note') }}
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
let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: {
        url: "{{ route('admin.stock-adjustments.index') }}",
        data: function(data) {
            data.brand = $('#brand_id').val(),
            data.isi = $('#isi_id').val(),
            data.jenjang = $('#jenjang_id').val(),
            data.kelas = $('#kelas_id').val(),
            data.halaman = $('#halaman_id').val()
        }
    },
    columns: [
        { data: 'placeholder', name: 'placeholder' },
        { data: 'date', name: 'date', class:'text-center' },
        { data: 'operation', name: 'operation', class:'text-center' },
        { data: 'note', name: 'note' },
        { data: 'actions', name: '{{ trans('global.actions') }}', class:'text-center' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-StockAdjustment').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

    $("#filterform").submit(function(event) {
        event.preventDefault();
        table.ajax.reload();
    });

});

</script>
@endsection
