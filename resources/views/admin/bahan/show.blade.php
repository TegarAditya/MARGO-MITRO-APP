@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.bahan.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            {{-- <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.bahan.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div> --}}
            <h3 class="mt-1">History Product Movement</h3>
            <h5 class="mt-2 mb-4">#BAHAN: {{ $product->name }}</h5>
            <div class="mb-3 table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-StockMovement">
                    <thead>
                        <tr>
                            <th></th>
                            <th>
                                {{ trans('cruds.stockMovement.fields.type') }}
                            </th>
                            <th>
                                {{ trans('cruds.stockMovement.fields.reference') }}
                            </th>
                            <th>
                                {{ trans('cruds.stockMovement.fields.quantity') }}
                            </th>
                            <th>
                                Stock
                            </th>
                            <th>
                                {{ trans('cruds.stockMovement.fields.created_at') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $stock_actual = $product->stock;
                        @endphp
                        @foreach($stockMovements as $key => $stockMovement)
                            <tr data-entry-id="{{ $stockMovement->id }}">
                                <td></td>
                                <td>
                                    {{ App\Models\StockMovement::TYPE_SELECT[$stockMovement->type] ?? '' }}
                                </td>
                                <td>
                                    @if ($stockMovement->type == 'order')
                                        {{ $stockMovement->referensi->no_order }}
                                    @elseif ($stockMovement->type == 'faktur')
                                        {{ $stockMovement->referensi->no_invoice }}
                                    @elseif ($stockMovement->type == 'adjustment')
                                        {{ $stockMovement->referensi->date.'('.App\Models\StockAdjustment::OPERATION_SELECT[$stockMovement->referensi->operation] .')' }}
                                    @endif
                                </td>
                                <td>
                                    {{ $stockMovement->quantity ?? '' }}
                                </td>
                                <td>
                                    {{ $stock_actual }}
                                </td>
                                <td>
                                    {{ $stockMovement->created_at ?? '' }}
                                </td>
                            </tr>
                        @php
                            $stock_actual = $stock_actual - $stockMovement->quantity;
                        @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
            <h3 class="mt-3 mb-4">#BAHAN: {{ $product->name }}</h3>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th class="text-left" width="20%" style="padding-left: 20px">
                            {{ trans('cruds.bahan.fields.name') }}
                        </th>
                        <td>
                            {{ $product->name }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-left" width="20%" style="padding-left: 20px">
                            {{ trans('cruds.bahan.fields.slug') }}
                        </th>
                        <td>
                            {{ $product->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-left" width="20%" style="padding-left: 20px">
                            {{ trans('cruds.bahan.fields.description') }}
                        </th>
                        <td>
                            {{ $product->description }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-left" width="20%" style="padding-left: 20px">
                            {{ trans('cruds.bahan.fields.category') }}
                        </th>
                        <td>
                            {{ $product->category->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-left" width="20%" style="padding-left: 20px">
                            {{ trans('cruds.bahan.fields.hpp') }}
                        </th>
                        <td>
                            @money($product->hpp)
                        </td>
                    </tr>
                    <tr>
                        <th class="text-left" width="20%" style="padding-left: 20px">
                            {{ trans('cruds.bahan.fields.price') }}
                        </th>
                        <td>
                            @money($product->price)
                        </td>
                    </tr>
                    <tr>
                        <th class="text-left" width="20%" style="padding-left: 20px">
                            {{ trans('cruds.bahan.fields.finishing_cost') }}
                        </th>
                        <td>
                            @money($product->finishing_cost)
                        </td>
                    </tr>
                    <tr>
                        <th class="text-left" width="20%" style="padding-left: 20px">
                            {{ trans('cruds.bahan.fields.stock') }}
                        </th>
                        <td>
                            {{ $product->stock }} {{ $product->unit->name }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-left" width="20%" style="padding-left: 20px">
                            {{ trans('cruds.bahan.fields.min_stock') }}
                        </th>
                        <td>
                            {{ $product->min_stock }} {{ $product->unit->name }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-left" width="20%" style="padding-left: 20px">
                            {{ trans('cruds.bahan.fields.foto') }}
                        </th>
                        <td>
                            @foreach($product->foto as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $media->getUrl('thumb') }}">
                                </a>
                            @endforeach
                        </td>
                    </tr>
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
    order: [[ 5, 'desc' ]],
    pageLength: 20,
  });
  let table = $('.datatable-StockMovement:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
