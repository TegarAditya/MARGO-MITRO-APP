@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        @if (!$salesperson->id)
            Tambah Sales
        @else
            Edit Sales
        @endif
    </div>

    <div class="card-body">
        @if (session()->has('error-message'))
            <p class="text-danger">
                {{session()->get('error-message')}}
            </p>
        @endif

        <form method="POST" action="{{ !$salesperson->id ? route('admin.salespeople.store') : route("admin.salespeople.update", [$salesperson->id]) }}" enctype="multipart/form-data" id="formsales">
            @method(!$salesperson->id ? 'POST' : 'PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.salesperson.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $salesperson->name) }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.salesperson.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="alamat-action mb-4">
                    <div class="row align-items-end">
                        <div class="col-4">
                            <div class="form-group m-0">
                                <label for="cities">Pilih Area Pemasaran</label>
                                <select class="form-control select2 city-options field-select2"
                                    name="cities" id="cities" data-placeholder="Pilih Area Pemasaran">
                                    <option></option>

                                    @foreach($cities as $id => $entry)
                                        <option
                                            value="{{ $id }}"
                                            data-id="{{ $entry->id }}"
                                        >{{ $entry->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('cities'))
                                    <span class="text-danger">{{ $errors->first('cities') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-auto">
                            <button type="button" class="btn py-1 border alamat-add">Tambah</button>
                        </div>
                    </div>
                </div>

                <h5>Area Pemasaran dan Alamat</h5>

                <div class="alamat-list">
                    @if ($salesperson->adresses->count())
                        @each('admin.salespeople.parts.item-alamat', $salesperson->adresses, 'detail')
                    @else
                        <div class="alamat-empty">
                            <p>Belum ada alamat yang ditambahkan</p>
                        </div>
                    @endif
                </div>

                <div class="alamat-faker d-none">
                    @include('admin.salespeople.parts.item-alamat', ['detail' => new App\Models\AlamatSale])
                </div>

            </div>
            <div class="form-group">
                <label for="telephone">{{ trans('cruds.salesperson.fields.telephone') }}</label>
                <input class="form-control {{ $errors->has('telephone') ? 'is-invalid' : '' }}" type="text" name="telephone" id="telephone" value="{{ old('telephone', $salesperson->telephone) }}">
                @if($errors->has('telephone'))
                    <span class="text-danger">{{ $errors->first('telephone') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.salesperson.fields.telephone_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="company">{{ trans('cruds.salesperson.fields.company') }}</label>
                <input class="form-control {{ $errors->has('company') ? 'is-invalid' : '' }}" type="text" name="company" id="company" value="{{ old('company', $salesperson->company) }}">
                @if($errors->has('company'))
                    <span class="text-danger">{{ $errors->first('company') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.salesperson.fields.company_helper') }}</span>
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

@push('styles')
<style>
.item-alamat {
    padding: .5rem 0;
    transition: 250ms ease-in-out;
}
.item-alamat + .item-alamat {
    border-top: 1px solid #cecece;
}
.item-alamat.highlight {
    background-color: rgba(32, 201, 151, .25);
}
</style>
@endpush

@section('scripts')
<script>
    (function($, numeral) {
        $(function() {
            var form = $('#formsales');

            var alamats = form.find('.alamat-list');
            var cityOpts = form.find('.city-options');
            var alamatAdd = form.find('.alamat-add');
            var alamatFake = form.find('.alamat-faker > .item-alamat');
            var alamatEmpty = form.find('.alamat-faker > .alamat-empty');

            $('.field-select2').each((index, item) => {
                const el = $(item);
                const placeholder = el.data('placeholder');

                placeholder && el.select2({
                    placeholder,
                });
            });

            var bindAlamat = function(alamat) {
                var highlightTO;

                alamat.find('.detail-delete').on('click', function(e) {
                    alamat.remove();

                    if (!alamats.children('.item-alamat').length) {
                        alamatEmpty.clone().appendTo(alamats);
                    }
                });

                alamat.on('highlight', function() {
                    highlightTO && clearTimeout(highlightTO);

                    alamat.addClass('highlight');

                    highlightTO = setTimeout(() => {
                        alamat.removeClass('highlight');
                    }, 1250);
                });
            };

            alamats.children('.item-alamat').each(function(i, item) {
                var alamat = $(item);

                bindAlamat(alamat);
            });

            alamatAdd.on('click', function(e) {
                e.preventDefault();

                var selected = cityOpts.children(':selected').first();
                var alamat = alamatFake.clone();
                var exists = alamats.children('.item-alamat[data-id="'+selected.data('id')+'"]');

                if (exists.length) {
                    exists.trigger('highlight');

                    return void(0);
                }

                if (!selected.data('id')) {
                    return void(0);
                }

                alamat.attr('data-id', selected.data('id'));
                alamat.find('.detail-kota').html(selected.html());
                alamat.find('.detail-alamat').val('')
                    .attr('name', 'alamat['+selected.data('id')+'][alamat]')
                    .attr('required', true)

                !alamats.children('.item-alamat').length && alamats.html('');
                alamat.appendTo(alamats);

                bindAlamat(alamat);
                cityOpts.val('').trigger('change');
            });
        });
    })(jQuery, window.numeral);
    </script>
@endsection
