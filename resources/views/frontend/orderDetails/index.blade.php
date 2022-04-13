@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('order_detail_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.order-details.create') }}">
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
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-OrderDetail">
                            <thead>
                                <tr>
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
                            <tbody>
                                @foreach($orderDetails as $key => $orderDetail)
                                    <tr data-entry-id="{{ $orderDetail->id }}">
                                        <td>
                                            {{ $orderDetail->order->date ?? '' }}
                                        </td>
                                        <td>
                                            {{ $orderDetail->product->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $orderDetail->quantity ?? '' }}
                                        </td>
                                        <td>
                                            {{ $orderDetail->unit_price ?? '' }}
                                        </td>
                                        <td>
                                            {{ $orderDetail->price ?? '' }}
                                        </td>
                                        <td>
                                            {{ $orderDetail->total ?? '' }}
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
  let table = $('.datatable-OrderDetail:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection