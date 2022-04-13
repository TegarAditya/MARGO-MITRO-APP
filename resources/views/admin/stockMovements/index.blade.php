@extends('layouts.admin')
@section('content')
@can('stock_movement_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.stock-movements.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.stockMovement.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.stockMovement.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-StockMovement">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.stockMovement.fields.reference') }}
                    </th>
                    <th>
                        {{ trans('cruds.stockMovement.fields.type') }}
                    </th>
                    <th>
                        {{ trans('cruds.stockMovement.fields.product') }}
                    </th>
                    <th>
                        {{ trans('cruds.stockMovement.fields.quantity') }}
                    </th>
                    <th>
                        {{ trans('cruds.stockMovement.fields.created_at') }}
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
    ajax: "{{ route('admin.stock-movements.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'reference', name: 'reference' },
{ data: 'type', name: 'type' },
{ data: 'product_name', name: 'product.name' },
{ data: 'quantity', name: 'quantity' },
{ data: 'created_at', name: 'created_at' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 5, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-StockMovement').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection