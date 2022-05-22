<div class="tab-pembayaran pt-3">
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
        <label class="required" for="tagihan_id">Order</label>
        <select class="form-control select2 {{ $errors->has('tagihan') ? 'is-invalid' : '' }}" name="tagihan_id" id="tagihan_id" required>
            <option value="">Please Select</option>

            @foreach($tagihans as $entry)
                <option
                    value="{{ $entry->id }}"
                    data-total="{{ $entry->total }}"
                    data-saldo="{{ $entry->saldo }}"
                    data-selisih="{{ $entry->selisih }}"
                    {{(
                        old('tagihan_id') ? old('tagihan_id') : $pembayaran->tagihan->id ?? ''
                    ) == $entry->id ? 'selected' : (
                        request('tagihan_id') == $entry->id ? 'selected' : ''
                    ) }}
                >{{ $entry->order->no_order }}</option>
            @endforeach
        </select>
        @if($errors->has('tagihan'))
            <span class="text-danger">{{ $errors->first('tagihan') }}</span>
        @endif
        <span class="help-block">{{ trans('cruds.pembayaran.fields.tagihan_helper') }}</span>
    </div>

    <div class="detail-tagihan mb-3" style="display: none; margin-top: -.5rem">
        <p class="mb-0 font-weight-bold">Detail Order</p>

        <div class="row">
            <div class="col-auto">
                <p class="mb-0">
                    <small class="font-weight-bold">Total Tagihan</small>
                    <br />
                    <span class="tagihan-total"></span>
                </p>
            </div>

            <div class="col-auto">
                <p class="mb-0">
                    <small class="font-weight-bold">Total Pembayaran</small>
                    <br />
                    <span class="tagihan-saldo"></span>
                </p>
            </div>

            <div class="col-auto">
                <p class="mb-0">
                    <small class="font-weight-bold">Sisa Tagihan</small>
                    <br />
                    <span class="tagihan-selisih">x</span>
                </p>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="required" for="nominal">Nominal Bayar</label>
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
            value="Rp{{ number_format($pembayaran->bayar, 0, ',', '.') }}"
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
        var tagihan = form.find('[name="tagihan_id"]');
        var nominal = form.find('[name="nominal"]');
        var diskonTypes = form.find('[name="diskon_type"]');
        var diskonAmount = form.find('[name="diskon_amount"]');
        var diskon = form.find('[name="diskon"]');
        var bayar = form.find('[name="bayar"]');
        var bayarText = form.find('[name="bayar_text"]');

        var tagihanDetail = form.find('.detail-tagihan');
        var tagihanTotal = tagihanDetail.find('.tagihan-total');
        var tagihanSaldo = tagihanDetail.find('.tagihan-saldo');
        var tagihanSelisih = tagihanDetail.find('.tagihan-selisih');

        tagihan.on('change', function(e) {
            var selected = tagihan.find('option').filter(':selected');
            var total = Math.abs(selected.data('total'));
            var saldo = Math.abs(selected.data('saldo'));
            var selisih = Math.abs(selected.data('selisih'));

            if (!isNaN(total) && !isNaN(saldo) && !isNaN(selisih)) {
                nominal.attr('max', selisih);
                selisih < parseFloat(nominal.val()) && nominal.val(selisih).trigger('change');

                tagihanDetail.show();
                tagihanTotal.html(numeral(total).format('$0,0'));
                tagihanSaldo.html(numeral(saldo).format('$0,0'));
                tagihanSelisih.html(numeral(selisih).format('$0,0'));
            } else {
                tagihanDetail.hide();
            }

            console.log("ASDASD", total, saldo, selisih);
        }).trigger('change');

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
            var max = Math.abs(nominal.attr('max'));
            var nominalVal = parseFloat(nominal.val()) || 0;
            var diskonVal = parseFloat(diskonAmount.val()) || 0;
            var diskonRp = diskonTypes.filter(':checked').val() !== 'percent' ? diskonVal : (nominalVal * diskonVal / 100);
            var value = nominalVal - diskonRp;

            value = (max && max < value) ? max : value;

            bayar.add(nominal).val(value);
            bayarText.val(numeral(value).format('$0,0'));
            diskon.val(diskonRp);
        });
    });
})(jQuery);
</script>
@endpush
