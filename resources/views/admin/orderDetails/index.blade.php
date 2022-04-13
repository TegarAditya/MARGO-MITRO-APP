@extends('layouts.admin')
@section('content')
@can('order_detail_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.order-details.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.orderDetail.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.orderDetail.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-OrderDetail">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.orderDetail.fields.order') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderDetail.fields.product') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderDetail.fields.quantity') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderDetail.fields.unit_price') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderDetail.fields.price') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderDetail.fields.total') }}
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
    ajax: "{{ route('admin.order-details.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'order_date', name: 'order.date' },
{ data: 'product_name', name: 'product.name' },
{ data: 'quantity', name: 'quantity' },
{ data: 'unit_price', name: 'unit_price' },
{ data: 'price', name: 'price' },
{ data: 'total', name: 'total' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-OrderDetail').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection