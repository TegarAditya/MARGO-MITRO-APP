@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('production_order_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.production-orders.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.productionOrder.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'ProductionOrder', 'route' => 'admin.production-orders.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.productionOrder.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-ProductionOrder">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.productionOrder.fields.po_number') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.productionOrder.fields.no_spk') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.productionOrder.fields.productionperson') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.productionOrder.fields.date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.productionOrder.fields.created_by') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productionOrders as $key => $productionOrder)
                                    <tr data-entry-id="{{ $productionOrder->id }}">
                                        <td>
                                            {{ $productionOrder->po_number ?? '' }}
                                        </td>
                                        <td>
                                            {{ $productionOrder->no_spk ?? '' }}
                                        </td>
                                        <td>
                                            {{ $productionOrder->productionperson->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $productionOrder->date ?? '' }}
                                        </td>
                                        <td>
                                            {{ $productionOrder->created_by->name ?? '' }}
                                        </td>
                                        <td>
                                            @can('production_order_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.production-orders.show', $productionOrder->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('production_order_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.production-orders.edit', $productionOrder->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('production_order_delete')
                                                <form action="{{ route('frontend.production-orders.destroy', $productionOrder->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('production_order_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.production-orders.massDestroy') }}",
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
  let table = $('.datatable-ProductionOrder:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection