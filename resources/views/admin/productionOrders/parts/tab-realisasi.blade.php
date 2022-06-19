<div class="order-realisasi pt-3">
    <div class="row align-items-center mb-2">
        <div class="col">
            <h5 class="mb-0">Riwayat Realisasi</h5>
        </div>

        <div class="col-auto">
            <a href="{{ route('admin.realisasis.create', ['production_order_id' => $productionOrder->id]) }}" class="btn btn-sm btn-success">Tambah Realisasi</a>
        </div>
    </div>

    <table class="table table-bordered table-realisasi">
        <thead>
            <tr>
                <th rowspan="2" width="1%">No.</th>
                <th rowspan="2" width="120">No. Realisasi</th>
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

        @if ($productionOrder && count($productionOrder->realisasis))
            @foreach ($productionOrder->realisasis as $row)
                <tbody>
                    @php
                    $rowspan = $row->realisasi_details->count();
                    $link = route('admin.realisasis.show', $row->id);
                    $print = function($type) use ($row) {
                        return route('admin.realisasis.show', ['realisasi' => $row->id, 'print' => $type]);
                    };
                    $is_out = 0 < $row->nominal;
                    @endphp

                    @foreach ($row->realisasi_details as $detail)
                        <tr>
                            @if ($loop->first)
                                <td rowspan="{{ $rowspan }}">{{ $loop->iteration }}</td>
                                <td rowspan="{{ $rowspan }}">{{ $row->no_realisasi }}</td>
                                <td rowspan="{{ $rowspan }}">{{ $row->date }}</td>
                            @endif

                            <td>
                                @if ($product = $detail->product)
                                    <p class="m-0">
                                        <span>{{ $product->name }}</span>
                                        <br />
                                        <span class="text-xs text-muted">
                                            @money($detail->price)
                                        </span>
                                    </p>
                                @else
                                    <p class="m-0">Produk</p>
                                @endif
                            </td>
                            <td class="text-center">{{ abs($detail->qty) }}</td>
                            <td class="text-right">@money(abs($detail->total))</td>
                            
                            @if ($loop->first)
                                <td rowspan="{{ $rowspan }}" class="text-right">@money(abs($row->nominal))</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            @endforeach
        @else
            <tbody>
                <tr>
                    <td colspan="8" class="text-center">
                        <p class="mb-0">Belum ada riwayat realisasi</p>
                    </td>
                </tr>
            </tbody>
        @endforelse
    </table>

    <div class="row mt-4">
        <div class="col">
            <a href="#tab-1" class="btn btn-default border modelTabs-nav">Sebelumnya</a>
        </div>
    </div>
</div>

@push('styles')
<style>
.table-invoice tbody:hover td {
    background-color: #efefef;
}
</style>
@endpush

@push('scripts')
<script>
(function($, numeral) {
    $(function() {
        var form = $('#modelForm');

    });
})(jQuery, window.numeral);
</script>
@endpush
