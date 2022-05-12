<div class="order-faktur pt-3">
    <div class="row align-items-center mb-2">
        <div class="col">
            <h5 class="mb-0">Riwayat Faktur</h5>
        </div>

        <div class="col-auto">
            <a href="{{ route('admin.invoices.create', ['order_id' => $order->id]) }}" class="btn btn-sm btn-success">Tambah Faktur</a>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th rowspan="2" width="1%">No.</th>
                <th rowspan="2">No. Surat Jalan</th>
                <th rowspan="2">No. Invoice</th>
                <th rowspan="2" width="120">Tanggal</th>
                <th colspan="3" class="text-center py-2">Produk</th>
                <th rowspan="2" class="text-center" width="1%">Total</th>
            </tr>

            <tr>
                <th class="pt-2">Nama</th>
                <th class="text-center pt-2" width="1%">Qty</th>
                <th class="text-center pt-2" width="1%">Subtotal</th>
            </tr>
        </thead>

        <tbody>
            @if ($order && count($order->invoices))
                @foreach ($order->invoices as $row)
                    @php
                    $rowspan = $row->invoice_details->count();
                    @endphp

                    @foreach ($row->invoice_details as $detail)
                        <tr>
                            @if ($loop->first)
                                <td rowspan="{{ $rowspan }}">{{ $loop->iteration }}</td>
                                <td rowspan="{{ $rowspan }}">{{ $row->no_suratjalan }}</td>
                                <td rowspan="{{ $rowspan }}">{{ $row->no_invoice }}</td>
                                <td rowspan="{{ $rowspan }}">{{ $row->date }}</td>
                            @endif

                            <td>
                                @if ($product = $detail->product)
                                    <p class="m-0">
                                        <span>{{ $product->name }}</span>
                                        <br />
                                        <span class="text-xs text-muted">
                                            Rp{{ number_format($detail->price) }}
                                        </span>
                                    </p>
                                @else
                                    <p class="m-0">Produk</p>
                                @endif
                            </td>
                            <td class="text-center">{{ $detail->quantity }}</td>
                            <td class="text-right">Rp{{ number_format($detail->total) }}</td>

                            @if ($loop->first)
                                <td rowspan="{{ $rowspan }}" class="text-right">Rp{{ number_format($row->nominal) }}</td>
                            @endif
                        </tr>
                    @endforeach
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center">
                        <p class="mb-0">Belum ada riwayat faktur</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="row mt-4">
        <div class="col">
            <a href="#order-1" class="btn btn-default border invoiceTabs-nav">Sebelumnya</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function($, numeral) {
    $(function() {
        var form = $('#invoiceForm');

    });
})(jQuery, window.numeral);
</script>
@endpush
