<div class="order-realisasi pt-3">
    <div class="row align-items-center mb-2">
        <div class="col">
            <h5 class="mb-0">Lanjutkan ke Finishing</h5>
        </div>
    </div>

    <table class="table table-bordered table-realisasi">
        <thead>
            <tr>
                <th rowspan="2" width="1%">No.</th>
                <th rowspan="2" width="120">No. Order Cetak</th>
                <th rowspan="2" width="120">Tanggal</th>
                <th colspan="3" class="text-center py-2">Produk</th>
                <th rowspan="2" class="text-center" width="1%">Total</th>
                <th rowspan="2" class="text-center" width="1%">Action</th>
            </tr>

            <tr>
                <th class="pt-2">Nama</th>
                <th class="text-center pt-2" width="1%">Qty Order</th>
                <th class="text-center pt-2" width="1%">Subtotal</th>
            </tr>
        </thead>

        @if ($productionOrder && count($productionOrder->production_order_details))
            @php
            $groups = $productionOrder->production_order_details->groupBy('group');
            @endphp
            @foreach ($groups as $group)
                <tbody>
                    @php
                    $rowspan = $group->count();
                    $total = $group->sum('ongkos_total');

                    $no = $loop->iteration;
                    @endphp

                    @foreach ($group as $detail)
                        <tr>
                            @if ($loop->first)
                                <td rowspan="{{ $rowspan }}">{{ $no }}</td>
                                <td rowspan="{{ $rowspan }}">
                                    {{ $productionOrder->no_order }}
                                </td>
                                <td rowspan="{{ $rowspan }}">{{ $productionOrder->date }}</td>
                            @endif

                            <td>
                                @if ($product = $detail->product)
                                    <p class="m-0">
                                        <span>{{ $product->name }}</span>
                                        <br />
                                        <span class="text-xs text-muted">
                                            @money($detail->ongkos_satuan)
                                        </span>
                                    </p>
                                @else
                                    <p class="m-0">Produk</p>
                                @endif
                            </td>
                            <td class="text-center">{{ abs($detail->order_qty) }}</td>
                            <td class="text-right">
                                <span class="text-nowrap">@money(abs($detail->ongkos_total))</span>
                            </td>
                            
                            @if ($loop->first)
                                <td rowspan="{{ $rowspan }}" class="text-right">
                                    <span class="text-nowrap">@money(abs($total))</span>
                                </td>

                                <td rowspan="{{ $rowspan }}" class="text-center">
                                    <span class="btn text-success btn-sm border-success finish-detail-check" data-id="{{ $detail->id }}" title="Lanjutkan ke Finishing">
                                        <i class="fa fa-check"></i>
                                    </span>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            @endforeach
        @else
            <tbody>
                <tr>
                    <td colspan="8" class="text-center">
                        <p class="mb-0">Belum ada produk yang dapat dilanjutkan ke Finishing</p>
                    </td>
                </tr>
            </tbody>
        @endforelse
    </table>

    <input type="hidden" name="finishing_group_ids" value="" id="finishing_group_ids" />

    <div class="row mt-3">
        <div class="col">
            <p class="mb-0">
                <span class="finishing_detail_count">0</span> dipilih 
            </p>
        </div>

        <div class="col text-right">
            <a href="/" id="finishingGroupSubmit" class="btn btn-success">Buat Finishing Order</a>
        </div>
    </div>

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

        var inputIds = form.find('#finishing_group_ids');
        var selectedText = form.find('.finishing_detail_count');
        var items = form.find('.finish-detail-check');

        $('a#finishingGroupSubmit').on('click', function(e) {
            e.preventDefault();

            inputIds.val() && form.trigger('submit');
        });

        items.each(function(index, item) {
            var el = $(item);

            bindFinishingDetail(el);
        });

        function bindFinishingDetail(detail) {
            var id = detail.data('id').toString();

            detail.on('click', function(e) {
                e.preventDefault();

                var ids = inputIds.val().split(',');
                var isChecked = ids.indexOf(id) >= 0;

                ids = ids.filter(function(item) {
                    return item && item != id;
                });

                if (!isChecked) {
                    detail.removeClass('border-success text-success');
                    detail.addClass('btn-success');

                    ids.push(id);

                    inputIds.val(ids.join(','));
                } else {
                    detail.removeClass('btn-success');
                    detail.addClass('border-success text-success');

                    inputIds.val(ids.join(','));
                }

                selectedText.html(ids.length);
            });
        };
    });
})(jQuery, window.numeral);
</script>
@endpush
