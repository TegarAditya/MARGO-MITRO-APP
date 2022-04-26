<div class="order-tagihan pt-3">
    <p class="mb-4">
        <strong>Total Tagihan</strong>
        <br />
        <span class="h5 mb-0 tagihan-total">Rp{{ number_format(data_get($order, 'tagihan.total', 0)) }}</span>
    </p>

    <div class="row align-items-center mb-2">
        <div class="col">
            <h5 class="mb-0">Riwayat Pembayaran</h5>
        </div>

        @if ($tagihan = $order->tagihan)
            <div class="col-auto">
                <a href="{{ route('admin.pembayarans.create', ['tagihan_id' => $tagihan->id]) }}" class="btn btn-sm btn-success">Tambah Pembayaran</a>
            </div>
        @endif
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th width="1%">No.</th>
                <th>No. Kwintansi</th>
                <th>Tanggal</th>
                <th class="text-center" width="1%">Nominal</th>
                <th class="text-center" width="1%">Diskon</th>
                <th class="text-center" width="1%">Bayar</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($order->pembayarans as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->no_kwitansi }}</td>
                    <td>{{ $row->tanggal }}</td>
                    <td class="text-right">Rp{{ number_format($row->nominal) }}</td>
                    <td class="text-right">{{ !$row->diskon ? '-' : 'Rp'.number_format($row->diskon) }}</td>
                    <td class="text-right">Rp{{ number_format($row->bayar) }}</td>
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
                <td colspan="5" class="text-right">
                    <strong>Sisa Tagihan</strong>
                </td>

                <td>
                    Rp{{ number_format($tagihan->saldo) }}
                </td>
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
