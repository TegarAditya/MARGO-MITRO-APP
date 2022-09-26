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
                    @foreach($prices as $price)
                        <option data-price="{{ $price->price }}" value="{{ $price->id }}" {{ old('price_id') == $price->id ? 'selected' : '' }}>{{ $price->nama_harga }}</option>
                    @endforeach
                </select>
                @if($errors->has('price'))
                    <span class="text-danger">{{ $errors->first('price') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.priceDetail.fields.price_helper') }}</span>
            </div>
            <input id="price" type="hidden" name="price" />
            <div class="form-group">
                <label class="required" for="diskon">{{ trans('cruds.priceDetail.fields.diskon') }}</label>
                <x-admin.form-group
                    class="form-control {{ $errors->has('diskon') ? 'is-invalid' : '' }}"
                    type="number"
                    id="diskon"
                    name="diskon"
                    containerClass=" m-0"
                    boxClass=" px-2 py-0"
                    value="0"
                    min="1"
                >
                    <x-slot name="left">
                        <span class="text-sm mr-1">%</span>
                    </x-slot>
                </x-admin.form-group>
                @if($errors->has('diskon'))
                    <span class="text-danger">{{ $errors->first('diskon') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.priceDetail.fields.diskon_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="custom_price">{{ trans('cruds.priceDetail.fields.custom_price') }}</label>
                <input id="custom_price" type="hidden" name="custom_price" />
                <x-admin.form-group
                    type="text"
                    id="custom_pricetext"
                    name="custom_pricetext"
                    containerClass=" m-0"
                    boxClass=" px-2 py-0"
                    class="form-control {{ $errors->has('custom_price') ? 'is-invalid' : '' }}"
                    value="{{ old('custom_price', '') }}"
                    min="0"
                >
                    <x-slot name="left">
                        <span class="text-sm mr-1">Rp</span>
                    </x-slot>
                </x-admin.form-group>
                @if($errors->has('custom_price'))
                    <span class="text-danger">{{ $errors->first('custom_price') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.priceDetail.fields.custom_price_helper') }}</span>
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

@push('scripts')
<script>
(function($, numeral) {
    $(function() {
        var price_id = $('#price_id');
        var price = $('#price');
        var diskon = $('#diskon');
        var customprice = $('#custom_price');
        var custompriceText = $('#custom_pricetext');

        var calculatePrice = function() {
            let harga = parseInt($('#price_id').find(':selected').data('price'));
            let potongan = parseInt(diskon.val());
            let price = Math.round(harga - ((potongan/100) * harga));
            customprice.val(price).trigger('change');
        };

        price_id.on('change keyup blur', function(e) {
            let harga = $('#price_id').find(':selected').data('price');
            price.val(harga);
            calculatePrice();
        }).trigger('change');

        diskon.on('change keyup blur', function(e) {
            var value = parseFloat(diskon.val()) || 0;
            if (value > 100) {
                diskon.val(100);
            }
            calculatePrice();
        });

        customprice.on('change keyup blur', function(e) {
            var value = numeral(e.target.value);
            custompriceText.val(value.format('0,0'));
        }).trigger('change');

        custompriceText.on('change keyup blur', function(e) {
            var value = numeral(e.target.value);

            custompriceText.val(value.format('0,0'));
            customprice.val(value.value()).trigger('change');
        }).trigger('change');
    });
})(jQuery, window.numeral);
</script>
@endpush
