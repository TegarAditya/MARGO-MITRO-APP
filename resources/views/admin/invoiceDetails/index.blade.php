@extends('layouts.admin')
@section('content')
@can('invoice_detail_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.invoice-details.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.invoiceDetail.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.invoiceDetail.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-InvoiceDetail">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.invoiceDetail.fields.invoice') }}
                    </th>
                    <th>
                        {{ trans('cruds.invoiceDetail.fields.product') }}
                    </th>
                    <th>
                        {{ trans('cruds.invoiceDetail.fields.quantity') }}
                    </th>
                    <th>
                        {{ trans('cruds.invoiceDetail.fields.price') }}
                    </th>
                    <th>
                        {{ trans('cruds.invoiceDetail.fields.total') }}
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
    ajax: "{{ route('admin.invoice-details.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'invoice_no_invoice', name: 'invoice.no_invoice' },
{ data: 'product_name', name: 'product.name' },
{ data: 'quantity', name: 'quantity' },
{ data: 'price', name: 'price' },
{ data: 'total', name: 'total' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-InvoiceDetail').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection