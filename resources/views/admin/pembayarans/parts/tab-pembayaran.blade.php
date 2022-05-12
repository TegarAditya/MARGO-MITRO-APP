<div class="tab-pembayaran">
    <input type="hidden" name="diskon" value="{{ $pembayaran->diskon }}" />
    <input type="hidden" name="bayar" value="{{ $pembayaran->bayar }}" />

    <div class="form-group">
        <label for="no_kwitansi">{{ trans('cruds.pembayaran.fields.no_kwitansi') }}</label>
        <input class="form-control {{ $errors->has('no_kwitansi') ? 'is-invalid' : '' }}" type="text" name="no_kwitansi" id="no_kwitansi" value="{{ old('no_kwitansi', $pembayaran->no_kwitansi) }}" readonly placeholder="Otomatis">
        @if($errors->has('no_kwitansi'))
            <span class="text-danger">{{ $errors->first('no_kwitansi') }}</span>
        @endif
        <span class="help-block">{{ trans('cruds.pembayaran.fields.no_kwitansi_helper') }}</span>
    </div>
    <div class="form-group">
        <label class="required" for="tagihan_id">{{ trans('cruds.pembayaran.fields.tagihan') }}</label>
        <select class="form-control select2 {{ $errors->has('tagihan') ? 'is-invalid' : '' }}" name="tagihan_id" id="tagihan_id" required>
            @foreach($tagihans as $id => $entry)
                <option value="{{ $id }}" {{ (old('tagihan_id') ? old('tagihan_id') : $pembayaran->tagihan->id ?? '') == $id ? 'selected' : (
                    request('tagihan_id') == $id ? 'selected' : ''
                ) }}>{{ $entry }}</option>
            @endforeach
        </select>
        @if($errors->has('tagihan'))
            <span class="text-danger">{{ $errors->first('tagihan') }}</span>
        @endif
        <span class="help-block">{{ trans('cruds.pembayaran.fields.tagihan_helper') }}</span>
    </div>
    <div class="form-group">
        <label class="required" for="nominal">{{ trans('cruds.pembayaran.fields.nominal') }}</label>
        <x-admin.form-group
            type="number"
            id="nominal"
            name="nominal"
            containerClass=" m-0"
            boxClass=" px-2 py-0"
            value="{{ $pembayaran->nominal }}"
            min="1"
            required
        >
            <x-slot name="left">
                <span class="mr-1">Rp</span>
            </x-slot>
        </x-admin.form-group>
    </div>

    {{-- Diskon --}}
    <p class="font-weight-bold mb-1">{{ trans('cruds.pembayaran.fields.diskon') }}</p>

    <div class="row mb-3">
        @foreach ([
            [ 'type' => null, 'label' => 'Tidak Ada', 'prefix' => '' ],
            [ 'type' => 'percent', 'label' => 'Persen (%)', 'prefix' => '%' ],
            [ 'type' => 'value', 'label' => 'Nominal (Rp)', 'prefix' => 'Rp' ],
        ] as $diskon)
            <div class="col-auto">
                <div class="form-check">
                    <input
                        class="form-check-input"
                        type="radio"
                        name="diskon_type"
                        id="diskon_type-{{ $loop->iteration }}"
                        value="{{ $diskon['type'] }}"
                        data-prefix="{{ $diskon['prefix'] }}"
                        required
                        {{ $diskon['type'] == 'value' && $pembayaran->diskon ? 'checked' : '' }}
                    >

                    <label class="form-check-label" for="diskon_type-{{ $loop->iteration }}">
                        {{ $diskon['label'] }}
                    </label>
                </div>
            </div>
        @endforeach

        <div class="col-12 mt-2 diskon-nominal" style="display:{{ !$pembayaran->diskon ? 'none' : 'block'}}">
            <p class="mb-0 text-sm">Nominal Diskon</p>

            <x-admin.form-group
                type="number"
                id="diskon_amount"
                name="diskon_amount"
                containerClass=" m-0"
                boxClass=" px-2 py-0"
                value="{{ $pembayaran->diskon }}"
                min="1"
            >
                <x-slot name="left">
                    <span class="text-sm mr-1 diskon-prefix"></span>
                </x-slot>
            </x-admin.form-group>
        </div>
    </div>

    <div class="form-group">
        <label class="required" for="bayar">{{ trans('cruds.pembayaran.fields.bayar') }}</label>
        <x-admin.form-group
            type="text"
            id="bayar"
            name="bayar_text"
            containerClass=" m-0"
            boxClass=" px-2 py-0"
            value="Rp{{ number_format($pembayaran->bayar) }}"
            min="1"
            readonly
        />
    </div>
    <div class="form-group">
        <label class="required" for="tanggal">{{ trans('cruds.pembayaran.fields.tanggal') }}</label>
        <input class="form-control date {{ $errors->has('tanggal') ? 'is-invalid' : '' }}" type="text" name="tanggal" id="tanggal" value="{{ old('tanggal', $pembayaran->tanggal) }}" required>
        @if($errors->has('tanggal'))
            <span class="text-danger">{{ $errors->first('tanggal') }}</span>
        @endif
        <span class="help-block">{{ trans('cruds.pembayaran.fields.tanggal_helper') }}</span>
    </div>
    <div class="form-group">
        <button class="btn btn-danger" type="submit">
            {{ trans('global.save') }}
        </button>
    </div>
</div>

@push('scripts')
<script>
(function($) {
    $(function() {
        var form = $('#pembayaranForm');
        var nominal = form.find('[name="nominal"]');
        var diskonTypes = form.find('[name="diskon_type"]');
        var diskonAmount = form.find('[name="diskon_amount"]');
        var diskon = form.find('[name="diskon"]');
        var bayar = form.find('[name="bayar"]');
        var bayarText = form.find('[name="bayar_text"]');

        diskonTypes.on('change', function(e) {
            var el = $(e.currentTarget);
            var prefix = el.data('prefix');
            var value = el.val();
            var nominalVal = parseFloat(nominal.val()) || 0;
            var diskonVal = parseFloat(diskonAmount.val()) || 0;

            $('.diskon-prefix').html(prefix || '');
            $('.diskon-nominal')[!value ? 'hide' : 'show']();

            if ('percent' === value && diskonVal > 100) {
                diskonAmount.val(Math.round(diskonVal * 100 / nominalVal));
            } else if ('value' === value && diskonVal <= 100) {
                diskonAmount.val(Math.round(nominalVal * diskonVal / 100));
            }

            diskonAmount.trigger('change');
        }).filter(':checked').trigger('change');

        diskonAmount.on('change keyup blur', function(e) {
            var value = parseFloat(diskonAmount.val()) || 0;
            var isPercent = 'percent' === diskonTypes.filter(':checked').val();

            (isPercent && value) > 100 && diskonAmount.val(100);
        });

        nominal.add(diskonAmount).on('change keyup blur', function(e) {
            var nominalVal = parseFloat(nominal.val()) || 0;
            var diskonVal = parseFloat(diskonAmount.val()) || 0;
            var diskonRp = diskonTypes.filter(':checked').val() !== 'percent' ? diskonVal : (nominalVal * diskonVal / 100);
            var value = nominalVal - diskonRp;

            bayar.val(value);
            bayarText.val(numeral(value).format('$0,0'));
            diskon.val(diskonRp);
        });
    });
})(jQuery);
</script>
@endpush
