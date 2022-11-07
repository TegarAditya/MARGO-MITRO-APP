@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('production_order_detail_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.finishing-order-details.create') }}">
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
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-FinishingOrderDetail">
                            <thead>
                                <tr>
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
                            <tbody>
                                @foreach($finishingOrderDetails as $key => $finishingOrderDetail)
                                    <tr data-entry-id="{{ $finishingOrderDetail->id }}">
                                        <td>
                                            {{ $finishingOrderDetail->finishing_order->po_number ?? '' }}
                                        </td>
                                        <td>
                                            {{ $finishingOrderDetail->product->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $finishingOrderDetail->order_qty ?? '' }}
                                        </td>
                                        <td>
                                            {{ $finishingOrderDetail->prod_qty ?? '' }}
                                        </td>
                                        <td>
                                            {{ $finishingOrderDetail->ongkos_satuan ?? '' }}
                                        </td>
                                        <td>
                                            {{ $finishingOrderDetail->ongkos_total ?? '' }}
                                        </td>
                                        <td>
                                            @can('production_order_detail_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.finishing-order-details.show', $finishingOrderDetail->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('production_order_detail_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.finishing-order-details.edit', $finishingOrderDetail->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('production_order_detail_delete')
                                                <form action="{{ route('frontend.finishing-order-details.destroy', $finishingOrderDetail->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                                </form>
                                            @endcan

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
@can('production_order_detail_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.finishing-order-details.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
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

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-FinishingOrderDetail:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection