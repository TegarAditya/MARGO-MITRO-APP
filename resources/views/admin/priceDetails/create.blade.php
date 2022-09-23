@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.priceDetail.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.price-details.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="sales_id">{{ trans('cruds.priceDetail.fields.sales') }}</label>
                <select class="form-control select2 {{ $errors->has('sales') ? 'is-invalid' : '' }}" name="sales_id" id="sales_id" required>
                    @foreach($sales as $id => $entry)
                        <option value="{{ $id }}" {{ old('sales_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('sales'))
                    <span class="text-danger">{{ $errors->first('sales') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.priceDetail.fields.sales_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="price_id">{{ trans('cruds.priceDetail.fields.price') }}</label>
                <select class="form-control select2 {{ $errors->has('price') ? 'is-invalid' : '' }}" name="price_id" id="price_id" required>
                    @foreach($prices as $id => $entry)
                        <option value="{{ $id }}" {{ old('price_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('price'))
                    <span class="text-danger">{{ $errors->first('price') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.priceDetail.fields.price_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="diskon">{{ trans('cruds.priceDetail.fields.diskon') }}</label>
                <input class="form-control {{ $errors->has('diskon') ? 'is-invalid' : '' }}" type="number" name="diskon" id="diskon" value="{{ old('diskon', '') }}" step="0.01" required max="100">
                @if($errors->has('diskon'))
                    <span class="text-danger">{{ $errors->first('diskon') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.priceDetail.fields.diskon_helper') }}</span>
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
