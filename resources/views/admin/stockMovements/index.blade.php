@extends('layouts.admin')
@section('content')
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
                </tr>
                <tr>
                    <td>

                    </td>
                    <td>

                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            <option value="adjustment">Adjustment</option>
                            <option value="faktur">Sales</option>
                        </select>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($products as $key => $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
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
    ],
    orderCellsTop: true,
    order: [[ 4, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-StockMovement').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  let visibleColumnsIndexes = null;
$('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value

      let index = $(this).parent().index()
      if (visibleColumnsIndexes !== null) {
        index = visibleColumnsIndexes[index]
      }

      table
        .column(index)
        .search(value, strict)
        .draw()
  });
table.on('column-visibility.dt', function(e, settings, column, state) {
      visibleColumnsIndexes = []
      table.columns(":visible").every(function(colIdx) {
          visibleColumnsIndexes.push(colIdx);
      });
  })

});

</script>
@endsection
