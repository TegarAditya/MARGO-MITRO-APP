@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.orderDetail.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.order-details.update", [$orderDetail->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="order_id">{{ trans('cruds.orderDetail.fields.order') }}</label>
                            <select class="form-control select2" name="order_id" id="order_id" required>
                                @foreach($orders as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('order_id') ? old('order_id') : $orderDetail->order->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('order'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('order') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.orderDetail.fields.order_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="product_id">{{ trans('cruds.orderDetail.fields.product') }}</label>
                            <select class="form-control select2" name="product_id" id="product_id" required>
                                @foreach($products as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('product_id') ? old('product_id') : $orderDetail->product->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('product'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('product') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.orderDetail.fields.product_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="quantity">{{ trans('cruds.orderDetail.fields.quantity') }}</label>
                            <input class="form-control" type="number" name="quantity" id="quantity" value="{{ old('quantity', $orderDetail->quantity) }}" step="1" required>
                            @if($errors->has('quantity'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('quantity') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.orderDetail.fields.quantity_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="unit_price">{{ trans('cruds.orderDetail.fields.unit_price') }}</label>
                            <input class="form-control" type="number" name="unit_price" id="unit_price" value="{{ old('unit_price', $orderDetail->unit_price) }}" step="0.01" required>
                            @if($errors->has('unit_price'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('unit_price') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.orderDetail.fields.unit_price_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="price">{{ trans('cruds.orderDetail.fields.price') }}</label>
                            <input class="form-control" type="number" name="price" id="price" value="{{ old('price', $orderDetail->price) }}" step="0.01" required>
                            @if($errors->has('price'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('price') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.orderDetail.fields.price_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="total">{{ trans('cruds.orderDetail.fields.total') }}</label>
                            <input class="form-control" type="number" name="total" id="total" value="{{ old('total', $orderDetail->total) }}" step="0.01" required>
                            @if($errors->has('total'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('total') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.orderDetail.fields.total_helper') }}</span>
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