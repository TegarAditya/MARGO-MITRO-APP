@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.summaryOrder.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-SummaryOrder">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.summaryOrder.fields.preorder') }}
                        </th>
                        <th>
                            {{ trans('cruds.summaryOrder.fields.order') }}
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.no_order') }}
                        </th>
                        <th>
                            {{ trans('cruds.summaryOrder.fields.product') }}
                        </th>
                        <th>
                            {{ trans('cruds.summaryOrder.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.summaryOrder.fields.quantity') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($summaryOrders as $key => $summaryOrder)
                        <tr data-entry-id="{{ $summaryOrder->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $summaryOrder->preorder->no_preorder ?? '' }}
                            </td>
                            <td>
                                {{ $summaryOrder->order->no_order ?? '' }}
                            </td>
                            <td>
                                {{ $summaryOrder->order->no_order ?? '' }}
                            </td>
                            <td>
                                {{ $summaryOrder->product->name ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\SummaryOrder::TYPE_SELECT[$summaryOrder->type] ?? '' }}
                            </td>
                            <td>
                                {{ $summaryOrder->quantity ?? '' }}
                            </td>
                            <td>
                                @can('summary_order_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.summary-orders.show', $summaryOrder->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan



                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
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
  let table = $('.datatable-SummaryOrder:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection