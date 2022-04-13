@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('stock_movement_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.stock-movements.create') }}">
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
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-StockMovement">
                            <thead>
                                <tr>
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
                            <tbody>
                                @foreach($stockMovements as $key => $stockMovement)
                                    <tr data-entry-id="{{ $stockMovement->id }}">
                                        <td>
                                            {{ $stockMovement->reference ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\StockMovement::TYPE_SELECT[$stockMovement->type] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $stockMovement->product->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $stockMovement->quantity ?? '' }}
                                        </td>
                                        <td>
                                            {{ $stockMovement->created_at ?? '' }}
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
    order: [[ 4, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-StockMovement:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection