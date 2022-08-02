<div class="tab-pembayaran pt-3">
    <input type="hidden" name="diskon" value="{{ $pembayaran->diskon }}" />
    <input type="hidden" name="nominal" value="{{ $pembayaran->nominal }}" />
    <input type="hidden" name="bayar" value="{{ $pembayaran->nominal }}" />

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
                @php
                $order = $entry->order;

                if (!$order) { continue; }
                @endphp
                <option
                    value="{{ $entry->id }}"
                    data-total="{{ $order->invoices->sum('nominal') }}"
                    data-saldo="{{ $order->pembayarans->sum('nominal') }}"
                    data-sisa="{{ $order->sisa_tagihan }}"
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
                    <span class="tagihan-sisa">x</span>
                </p>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="required" for="bayar">Bayar</label>

        <x-admin.form-group
            type="text"
            id="bayar_text"
            name="bayar_text"
            containerClass=" m-0"
            boxClass=" px-2 py-0"
            value="{{ $pembayaran->bayar }}"
            data-editable="{{ !$pembayaran->id ? 'true' : 'false' }}"
            min="1"
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
        <label class="required" for="nominal">Nominal Bayar</label>

        <x-admin.form-group
            type="text"
            id="nominal"
            name="nominal_text"
            containerClass=" m-0"
            boxClass=" px-2 py-0"
            value="Rp{{ number_format($pembayaran->nominal, 0, ',', '.') }}"
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
        var nominalText = form.find('[name="nominal_text"]');
        var diskonTypes = form.find('[name="diskon_type"]');
        var diskonAmount = form.find('[name="diskon_amount"]');
        var diskon = form.find('[name="diskon"]');
        var bayar = form.find('[name="bayar"]');
        var bayarText = form.find('[name="bayar_text"]');

        var tagihanDetail = form.find('.detail-tagihan');
        var tagihanTotal = tagihanDetail.find('.tagihan-total');
        var tagihanSaldo = tagihanDetail.find('.tagihan-saldo');
        var tagihanSisa = tagihanDetail.find('.tagihan-sisa');

        bayarText.on('change keyup blur paste', function(e) {
            var value = numeral(e.target.value);

            bayarText.val(value.format('0,0'));
            bayar.val(value.value()).trigger('change');
        }).trigger('change');

        tagihan.on('change', function(e) {
            var selected = tagihan.find('option').filter(':selected');
            var total = Math.abs(selected.data('total'));
            var saldo = Math.abs(selected.data('saldo'));
            var sisa = Math.abs(selected.data('sisa'));

            if (!isNaN(total) && !isNaN(saldo) && !isNaN(sisa)) {
                if (bayar.data('editable')) {
                    bayar.attr('max', sisa);
                    sisa < parseFloat(bayar.val()) && bayar.val(sisa).trigger('change');
                }

                tagihanDetail.show();
                tagihanTotal.html(numeral(total).format('$0,0'));
                tagihanSaldo.html(numeral(saldo).format('$0,0'));
                tagihanSisa.html(numeral(sisa).format('$0,0'));
            } else {
                tagihanDetail.hide();
            }
        }).trigger('change');

        diskonTypes.on('change', function(e) {
            var el = $(e.currentTarget);
            var prefix = el.data('prefix');
            var value = el.val();
            var bayarVal = parseFloat(bayar.val()) || 0;
            var diskonVal = parseFloat(diskonAmount.val()) || 0;

            $('.diskon-prefix').html(prefix || '');
            $('.diskon-nominal')[!value ? 'hide' : 'show']();
            diskonAmount.attr('min', !value ? null : 1);

            if ('percent' === value && diskonVal > 100) {
                diskonAmount.val(Math.round(diskonVal * 100 / bayarVal));
            } else if ('value' === value && diskonVal <= 100) {
                diskonAmount.val(Math.round(bayarVal * diskonVal / 100));
            }

            diskonAmount.trigger('change');
        }).filter(':checked').trigger('change');

        diskonAmount.on('change keyup blur', function(e) {
            var value = parseFloat(diskonAmount.val()) || 0;
            var isPercent = 'percent' === diskonTypes.filter(':checked').val();

            (isPercent && value) > 100 && diskonAmount.val(100);
        });

        bayar.add(diskonAmount).on('change keyup blur', function(e) {
            var max = Math.abs(bayar.attr('max'));
            var bayarVal = parseFloat(bayar.val()) || 0;
            var diskonVal = parseFloat(diskonAmount.val()) || 0;
            var diskonType = diskonTypes.filter(':checked').val();
            var diskonCalc = diskonType !== 'percent' ? (
                diskonType !== 'value' ? 0 : diskonVal
            ) : ((diskonVal / 100)  * bayarVal);

            bayarVal = (max && max < bayarVal) ? max : bayarVal;

            var diskonRp = diskonCalc <= bayarVal ? diskonCalc : bayarVal;
            diskonVal = diskonCalc <= bayarVal ? diskonVal : (
                diskonType === 'percent' ? 100 : bayarVal
            );

            var value = Math.round(bayarVal + diskonRp);

            bayar.val(bayarVal);
            nominal.val(value);
            nominalText.val(numeral(value).format('$0,0'));
            diskon.val(diskonRp);
            diskonAmount.val(diskonVal);
        });
    });
})(jQuery);
</script>
@endpush
