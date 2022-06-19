<div class="order-tagihan pt-3">
    <div class="row mb-4">
        <div class="col-auto border-right pr-3 mr-2">
            <p class="mb-0">
                <strong>Total Order</strong>
                <br />
                <span class="h5 mb-0 tagihan-total">@money(data_get($order, 'tagihan.total', 0))</span>
            </p>
        </div>

        <div class="col-auto">
            <p class="mb-0">
                <strong>Total Tagihan</strong>
                <br />
                <span class="h5 mb-0 tagihan-total">@money($order->invoices->sum('nominal'))</span>
            </p>
        </div>

        <div class="col-auto">
            <p class="mb-0">
                <strong>Total Pembayaran</strong>
                <br />
                <span class="h5 mb-0 tagihan-total">@money($order->pembayarans->sum('nominal'))</span>
            </p>
        </div>

        <div class="col-auto">
            <p class="mb-0">
                <strong>Sisa Tagihan</strong>
                <br />
                <span class="h5 mb-0 tagihan-total">@money($order->sisa_tagihan)</span>
            </p>
        </div>
    </div>

    <div class="row align-items-center mb-2">
        <div class="col">
            <h5 class="mb-0">Riwayat Pembayaran</h5>
        </div>

        @if ($tagihan = $order->tagihan)
            <div class="col-auto">
                <a href="{{ route('admin.pembayarans.create', ['tagihan_id' => $tagihan->id]) }}" class="btn btn-sm btn-success{{ 0 >= $order->sisa_tagihan ? ' disabled' : '' }} ">Tambah Pembayaran</a>
            </div>
        @endif
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th width="1%">No.</th>
                <th>No. Kwintansi</th>
                <th>Tanggal</th>
                <th class="text-center" width="1%">Bayar</th>
                <th class="text-center" width="1%">Diskon</th>
                <th class="text-center" width="1%">Nominal</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($order->pembayarans as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <a href="{{ route('admin.pembayarans.edit', $row->id) }}">{{ $row->no_kwitansi }}</a>

                        <a href="{{ route('admin.pembayarans.show', [
                            'pembayaran' => $row->id,
                            'print' => 'on'
                        ]) }}" target="_blank" title="Cetak Kwitansi" class="ml-1">
                            <i class="fa fa-print text-dark"></i>
                        </a>
                    </td>
                    <td>{{ $row->tanggal }}</td>
                    <td class="text-right">@money($row->bayar)</td>
                    <td class="text-right">
                        @if (!$row->diskon)
                            <span>-</span>
                        @else
                            @money($row->diskon)
                        @endif
                    </td>
                    <td class="text-right">@money($row->nominal)</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">
                        <p class="mb-0">Belum ada riwayat pembayaran</p>
                    </td>
                </tr>
            @endforelse
        </tbody>

        @if ($order->pembayarans->count() && $tagihan = $order->tagihan)
            <tfoot>
                <tr>
                    <td colspan="5" class="text-right">
                        <strong>Total</strong>
                    </td>

                    <td class="text-right">
                        @money($order->pembayarans->sum('nominal'))
                    </td>
                </tr>

                <tr>
                    <td colspan="5" class="text-right py-2">
                        <strong>Total Tagihan</strong>
                    </td>

                    <td class="text-right py-2">
                        @money($order->invoices->sum('nominal'))
                    </td>
                </tr>

                <tr>
                    <td colspan="5" class="text-right py-2">
                        <strong>Sisa Tagihan</strong>
                    </td>

                    <td class="text-right py-2">
                        @money($order->sisa_tagihan)
                    </td>
                </tr>
            </tfoot>
        @endif
    </table>

    <div class="row mt-4">
        <div class="col">
            <a href="#order-2" class="btn btn-default border orderTabs-nav">Sebelumnya</a>
        </div>

        <div class="col-auto"></div>
    </div>
</div>

@push('scripts')
<script>
(function($, numeral) {
    $(function() {
        var form = $('#orderForm');

    });
})(jQuery, window.numeral);
</script>
@endpush
