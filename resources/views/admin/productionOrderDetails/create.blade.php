@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.productionOrderDetail.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.production-order-details.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="production_order_id">{{ trans('cruds.productionOrderDetail.fields.production_order') }}</label>
                <select class="form-control select2 {{ $errors->has('production_order') ? 'is-invalid' : '' }}" name="production_order_id" id="production_order_id">
                    @foreach($production_orders as $id => $entry)
                        <option value="{{ $id }}" {{ old('production_order_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('production_order'))
                    <span class="text-danger">{{ $errors->first('production_order') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productionOrderDetail.fields.production_order_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="product_id">{{ trans('cruds.productionOrderDetail.fields.product') }}</label>
                <select class="form-control select2 {{ $errors->has('product') ? 'is-invalid' : '' }}" name="product_id" id="product_id">
                    @foreach($products as $id => $entry)
                        <option value="{{ $id }}" {{ old('product_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('product'))
                    <span class="text-danger">{{ $errors->first('product') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productionOrderDetail.fields.product_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="order_qty">{{ trans('cruds.productionOrderDetail.fields.order_qty') }}</label>
                <input class="form-control {{ $errors->has('order_qty') ? 'is-invalid' : '' }}" type="number" name="order_qty" id="order_qty" value="{{ old('order_qty', '0') }}" step="1" required>
                @if($errors->has('order_qty'))
                    <span class="text-danger">{{ $errors->first('order_qty') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productionOrderDetail.fields.order_qty_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="prod_qty">{{ trans('cruds.productionOrderDetail.fields.prod_qty') }}</label>
                <input class="form-control {{ $errors->has('prod_qty') ? 'is-invalid' : '' }}" type="number" name="prod_qty" id="prod_qty" value="{{ old('prod_qty', '0') }}" step="1" required>
                @if($errors->has('prod_qty'))
                    <span class="text-danger">{{ $errors->first('prod_qty') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productionOrderDetail.fields.prod_qty_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="ongkos_satuan">{{ trans('cruds.productionOrderDetail.fields.ongkos_satuan') }}</label>
                <input class="form-control {{ $errors->has('ongkos_satuan') ? 'is-invalid' : '' }}" type="number" name="ongkos_satuan" id="ongkos_satuan" value="{{ old('ongkos_satuan', '0') }}" step="0.01" required>
                @if($errors->has('ongkos_satuan'))
                    <span class="text-danger">{{ $errors->first('ongkos_satuan') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productionOrderDetail.fields.ongkos_satuan_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="ongkos_total">{{ trans('cruds.productionOrderDetail.fields.ongkos_total') }}</label>
                <input class="form-control {{ $errors->has('ongkos_total') ? 'is-invalid' : '' }}" type="number" name="ongkos_total" id="ongkos_total" value="{{ old('ongkos_total', '0') }}" step="0.01" required>
                @if($errors->has('ongkos_total'))
                    <span class="text-danger">{{ $errors->first('ongkos_total') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productionOrderDetail.fields.ongkos_total_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection