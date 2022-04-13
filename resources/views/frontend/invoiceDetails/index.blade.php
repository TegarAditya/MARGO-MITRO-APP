@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('invoice_detail_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.invoice-details.create') }}">
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
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-InvoiceDetail">
                            <thead>
                                <tr>
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
                            <tbody>
                                @foreach($invoiceDetails as $key => $invoiceDetail)
                                    <tr data-entry-id="{{ $invoiceDetail->id }}">
                                        <td>
                                            {{ $invoiceDetail->invoice->no_invoice ?? '' }}
                                        </td>
                                        <td>
                                            {{ $invoiceDetail->product->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $invoiceDetail->quantity ?? '' }}
                                        </td>
                                        <td>
                                            {{ $invoiceDetail->price ?? '' }}
                                        </td>
                                        <td>
                                            {{ $invoiceDetail->total ?? '' }}
                                        </td>
                                        <td>



                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
  
  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-InvoiceDetail:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection