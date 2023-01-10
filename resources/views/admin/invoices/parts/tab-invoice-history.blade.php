<div class="order-faktur pt-3">
    <div class="row align-items-center mb-2">
        <div class="col">
            <h5 class="mb-0">Riwayat Faktur</h5>
        </div>
    </div>

    <table class="table table-bordered table-invoice">
        <thead>
            <tr>
                <th width="1%">No.</th>
                <th>No. Surat Jalan</th>
                <th>No. Invoice</th>
                <th class="text-center" width="15%">Tanggal</th>
                {{-- <th width="120">Masuk/Keluar</th> --}}
                {{-- <th colspan="3" class="text-center py-2">Produk</th> --}}
                <th class="text-center" width="15%">Total</th>
                <th class="text-center" width="10%"></th>
            </tr>
        </thead>

        @if ($order && count($order->invoices))
            @foreach ($order->invoices as $row)
                <tbody>
                    @php
                    $rowspan = $row->invoice_details->count();
                    $link = route('admin.invoices.edit', $row->id);
                    $print = function($type) use ($row) {
                        return route('admin.invoices.show', ['invoice' => $row->id, 'print' => $type]);
                    };
                    $is_out = 0 <= $row->nominal;

                    $no = $loop->iteration;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $no }}</td>
                        <td class="text-center">
                            <div class="d-flex">
                                <div class="flex-grow-1 pr-2">
                                    <a href="{{ $link }}">{{ $row->no_suratjalan }}</a>
                                </div>

                                <div class="mr-2">
                                    <a href="{{ $print('sj') }}" target="_blank">
                                        <i class="fa fa-print text-dark"></i>
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="d-flex">
                                <div class="flex-grow-1 pr-2">
                                    <a href="{{ $link }}">{{ $row->no_invoice }}</a>
                                </div>

                                <div class="mr-2">
                                    <a href="{{ $print('inv') }}" target="_blank">
                                        <i class="fa fa-print text-dark"></i>
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            {{ $row->date }}
                            <span class="text-center ml-1 {{ $is_out ? 'text-success' : 'text-danger' }}">
                                <strong class="text-xs">
                                    @if ($is_out)
                                        <i class="fa fa-arrow-up"></i>
                                    @else
                                        <i class="fa fa-arrow-down"></i>
                                    @endif
                                </strong>
                            </span>
                        </td>
                        {{-- <td class="{{ $is_out ? 'text-warning' : 'text-info' }} text-center">
                            {{ $is_out ? 'Keluar' : 'Masuk' }}
                        </td> --}}

                        {{-- <td class="text-center">{{ abs($detail->quantity) }}</td>
                        <td class="text-right">@money(abs($detail->total))</td> --}}

                        <td class="text-right">@money(abs($row->nominal))</td>

                        <td class="text-center">
                            <a href="{{ route('admin.invoices.edit', $row->id) }}" class="btn btn-md invoice-edit-btn">
                                <i class="fa fa-edit text-primary"></i>
                            </a>
                            <a href="{{ route('admin.invoices.destroy', $row->id) }}" class="btn btn-md invoice-delete-btn">
                                <i class="fa fa-trash text-danger"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            @endforeach
        @else
            <tbody>
                <tr>
                    <td colspan="8" class="text-center">
                        <p class="mb-0">Belum ada riwayat faktur</p>
                    </td>
                </tr>
            </tbody>
        @endforelse
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

        $('.invoice-delete-btn').on('click', function(e) {
            e.preventDefault();

            var el = $(e.currentTarget);
            var url = el.attr('href');
            var id = el.data('id');

            if (url && confirm('{{ trans('global.areYouSure') }}')) {
                $.ajax({
                    headers: {'x-csrf-token': _token},
                    method: 'POST',
                    url: url,
                    data: { _method: 'DELETE' }
                }).done(function () {
                    if (id == '{{ $invoice->id }}') {
                        return (location.href = '{{ route("admin.invoices.index") }}');
                    }

                    location.reload();
                });
            }
        });
    });
})(jQuery, window.numeral);
</script>
@endpush
