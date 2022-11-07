@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.price.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.prices.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.price.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.price.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="category_id">{{ trans('cruds.price.fields.category') }}</label>
                <select class="form-control select2 {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category_id" id="category_id" required>
                    @foreach($categories as $id => $entry)
                        <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>{{ $entry }} Halaman</option>
                    @endforeach
                </select>
                @if($errors->has('category'))
                    <span class="text-danger">{{ $errors->first('category') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.price.fields.category_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="price">{{ trans('cruds.price.fields.price') }}</label>
                <input id="price" type="hidden" name="price" />
                <x-admin.form-group
                    type="text"
                    id="pricetext"
                    name="pricetext"
                    containerClass=" m-0"
                    boxClass=" px-2 py-0"
                    class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}"
                    value="{{ old('price', '') }}"
                    min="0"
                >
                    <x-slot name="left">
                        <span class="text-sm mr-1">Rp</span>
                    </x-slot>
                </x-admin.form-group>
                @if($errors->has('price'))
                    <span class="text-danger">{{ $errors->first('price') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.price.fields.price_helper') }}</span>
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
        var price = $('#price');
        var priceText = $('#pricetext');
        priceText.on('change keyup blur', function(e) {
            var value = numeral(e.target.value);

            priceText.val(value.format('0,0'));
            price.val(value.value()).trigger('change');
        }).trigger('change');
    });
})(jQuery, window.numeral);
</script>
@endpush
