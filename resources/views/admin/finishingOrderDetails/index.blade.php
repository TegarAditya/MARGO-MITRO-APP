@extends('layouts.admin')
@section('content')
@can('production_order_detail_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.production-order-details.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.productionOrderDetail.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.productionOrderDetail.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-FinishingOrderDetail">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.productionOrderDetail.fields.production_order') }}
                    </th>
                    <th>
                        {{ trans('cruds.productionOrderDetail.fields.product') }}
                    </th>
                    <th>
                        {{ trans('cruds.productionOrderDetail.fields.order_qty') }}
                    </th>
                    <th>
                        {{ trans('cruds.productionOrderDetail.fields.prod_qty') }}
                    </th>
                    <th>
                        {{ trans('cruds.productionOrderDetail.fields.ongkos_satuan') }}
                    </th>
                    <th>
                        {{ trans('cruds.productionOrderDetail.fields.ongkos_total') }}
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
@can('production_order_detail_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.production-order-details.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.production-order-details.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'finishing_order_po_number', name: 'finishing_order.po_number' },
{ data: 'product_name', name: 'product.name' },
{ data: 'order_qty', name: 'order_qty' },
{ data: 'prod_qty', name: 'prod_qty' },
{ data: 'ongkos_satuan', name: 'ongkos_satuan' },
{ data: 'ongkos_total', name: 'ongkos_total' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-FinishingOrderDetail').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection