@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.productionOrderDetail.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.production-order-details.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="finishing_order_id">{{ trans('cruds.productionOrderDetail.fields.production_order') }}</label>
                            <select class="form-control select2" name="finishing_order_id" id="finishing_order_id">
                                @foreach($finishing_orders as $id => $entry)
                                    <option value="{{ $id }}" {{ old('finishing_order_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('production_order'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('production_order') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.productionOrderDetail.fields.production_order_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="product_id">{{ trans('cruds.productionOrderDetail.fields.product') }}</label>
                            <select class="form-control select2" name="product_id" id="product_id">
                                @foreach($products as $id => $entry)
                                    <option value="{{ $id }}" {{ old('product_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('product'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('product') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.productionOrderDetail.fields.product_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="order_qty">{{ trans('cruds.productionOrderDetail.fields.order_qty') }}</label>
                            <input class="form-control" type="number" name="order_qty" id="order_qty" value="{{ old('order_qty', '0') }}" step="1" required>
                            @if($errors->has('order_qty'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('order_qty') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.productionOrderDetail.fields.order_qty_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="prod_qty">{{ trans('cruds.productionOrderDetail.fields.prod_qty') }}</label>
                            <input class="form-control" type="number" name="prod_qty" id="prod_qty" value="{{ old('prod_qty', '0') }}" step="1" required>
                            @if($errors->has('prod_qty'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('prod_qty') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.productionOrderDetail.fields.prod_qty_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="ongkos_satuan">{{ trans('cruds.productionOrderDetail.fields.ongkos_satuan') }}</label>
                            <input class="form-control" type="number" name="ongkos_satuan" id="ongkos_satuan" value="{{ old('ongkos_satuan', '0') }}" step="0.01" required>
                            @if($errors->has('ongkos_satuan'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('ongkos_satuan') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.productionOrderDetail.fields.ongkos_satuan_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="ongkos_total">{{ trans('cruds.productionOrderDetail.fields.ongkos_total') }}</label>
                            <input class="form-control" type="number" name="ongkos_total" id="ongkos_total" value="{{ old('ongkos_total', '0') }}" step="0.01" required>
                            @if($errors->has('ongkos_total'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('ongkos_total') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection