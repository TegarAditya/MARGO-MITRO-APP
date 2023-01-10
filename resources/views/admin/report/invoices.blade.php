@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        History Pengiriman
    </div>

    <div class="card-body">
        <form action="" method="POST">
            @csrf

            <input type="hidden" name="export" value="{{ request('export') }}" />

            <div class="row">
                <div class="col row">
                    {{-- <div class="col-4">
                        <div class="form-group mb-0">
                            <label class="small mb-0" for="order_id">{{ trans('cruds.invoice.fields.order') }}</label>
                            <select class="form-control select2 {{ $errors->has('order_id') ? 'is-invalid' : '' }}" name="order_id" id="order_id">
                                <option value="">Semua Order</option>
                                @foreach($orders as $order)
                                    <option value="{{ $order->id }}" {{ (old('order_id') ? old('order_id') : (request('order_id') == $order->id ? 'selected' : '')) }}>{{ $order->no_order }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('order'))
                                <span class="text-danger">{{ $errors->first('order') }}</span>
                            @endif
                        </div>
                    </div> --}}

                    <div class="col-4">
                        <div class="form-group mb-0">
                            <label class="small mb-0" for="salesperson_id">Sales Person</label>
                            <select class="form-control select2 {{ $errors->has('salesperson_id') ? 'is-invalid' : '' }}" name="salesperson_id" id="salesperson_id">
                                <option value="">Semua Sales Person</option>
                                @foreach($salespersons as $sales)
                                    <option value="{{ $sales->id }}" {{ (old('salesperson_id') ? old('salesperson_id') : (request('salesperson_id') == $sales->id ? 'selected' : '')) }}>{{ $sales->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('salesperson_id'))
                                <span class="text-danger">{{ $errors->first('salesperson_id') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group  mb-0">
                            <label class="small mb-0" for="invoice_type">Jenis Invoice</label>
                            <select class="form-control select2 {{ $errors->has('order') ? 'is-invalid' : '' }}" name="invoice_type" id="invoice_type" required>
                                @foreach([
                                    'kirim' => 'Pengiriman',
                                    'retur' => 'Retur'
                                ] as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('invoice_type') ? old('invoice_type') : (request('invoice_type') == $id ? 'selected' : '')) }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('invoice_type'))
                                <span class="text-danger">{{ $errors->first('invoice_type') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-4">
                        <x-admin.form-group
                            type="text"
                            id="date"
                            name="date"
                            containerClass=" m-0"
                            boxClass=" px-2 py-1"
                            class="form-control-sm product-price"
                            value="{{ request('date', old('date'))}}"
                            placeholder="Pilih Tanggal"
                        >
                            <x-slot name="label">
                                <label class="small mb-0" for="date">Tanggal</label>
                            </x-slot>

                            <x-slot name="right">
                                <button type="button" class="btn btn-sm border-0 btn-default px-2 date-clear" data-action="+" style="display:{{ !request('date', old('date')) ? 'none' : 'block' }}">
                                    <i class="fa fa-times"></i>
                                </button>
                            </x-slot>
                        </x-admin.form-group>
                    </div>
                </div>

                <div class="col-auto align-self-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>

        <div class="row border-top pt-2 mt-2">
            <div class="col-auto">
                <a href="#" class="btn btn-default export-btn" data-export="excel">
                    Export Excel
                </a>
            </div>
        </div>

        <table class="mt-3 table table-bordered table-invoices">
            <thead>
                <tr>
                    <th></th>
                    <th>
                        {{ trans('cruds.invoice.fields.order') }}
                    </th>
                    <th width="120">
                        {{ trans('cruds.invoice.fields.date') }}
                    </th>
                    <th class="text-center">
                        Products
                    </th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th>
                        {{ trans('cruds.invoice.fields.nominal') }}
                    </th>
                </tr>
            </thead>

            @forelse ($invoices as $invoice)
                @php
                $order = $invoice->order;
                $rowspan = $invoice->invoice_details->count();
                $link = route('admin.invoices.edit', $invoice->id);
                $print = function($type) use ($invoice) {
                    return route('admin.invoices.show', ['invoice' => $invoice->id, 'print' => $type]);
                };
                $is_out = 0 <= $invoice->nominal;
                $no = $loop->iteration;
                @endphp

                <tbody>
                    @foreach ($invoice->invoice_details as $detail)
                        <tr>
                            @if ($loop->first)
                                <td rowspan="{{ $rowspan }}" class="align-middle text-center">{{ $no }}</td>
                                <td rowspan="{{ $rowspan }}">
                                    <div class="row">
                                        <div class="col-6">
                                            <p class="mb-0">
                                                <strong class="text-xs">No. Order</strong>
                                                <br />
                                                <a href="{{ route('admin.orders.show', $order->id) }}">{{ $order->no_order }}</a>
                                            </p>
                                        </div>

                                        <div class="col-6">
                                            <p class="mb-0">
                                                <strong class="text-xs">Sales Person</strong>
                                                <br />
                                                <span>{{ data_get($order, 'salesperson.name', '-') }}</span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="row pt-1 mt-1 border-top">
                                        <div class="col-6">
                                            <p class="mb-0">
                                                <strong class="text-xs">No. Invoice</strong>
                                                <br />
                                                <a href="{{ $link }}">{{ $invoice->no_invoice }}</a>
                                            </p>
                                        </div>

                                        <div class="col-6">
                                            <p class="mb-0">
                                                <strong class="text-xs">No. Surat Jalan</strong>
                                                <br />
                                                <a href="{{ $link }}">{{ $invoice->no_suratjalan }}</a>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td rowspan="{{ $rowspan }}" class="align-middle">
                                    {{ $invoice->date }}
                                    <span class="ml-1 {{ $is_out ? 'text-success' : 'text-danger' }}">
                                        @if ($is_out)
                                            <i class="fa fa-arrow-up"></i>
                                        @else
                                            <i class="fa fa-arrow-down"></i>
                                        @endif
                                    </span>
                                </td>
                            @endif

                            <td class="align-middle">
                                @if ($product = $detail->product)
                                    <p class="text-sm m-0">
                                        <span>{{ $product->nama_buku }}</span>
                                        <br />
                                        <span class="text-xs text-muted">@money($detail->price)</span>
                                    </p>
                                @else
                                    <p class="m-0">Produk</p>
                                @endif
                            </td>
                            <td class="text-center align-middle">{{ abs($detail->quantity) }}</td>
                            <td class="text-right align-middle">@money(abs($detail->total))</td>

                            @if ($loop->first)
                                <td rowspan="{{ $rowspan }}" class="text-right align-middle">@money(abs($invoice->nominal))</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            @empty
                <tbody>
                    <tr>
                        <td colspan="8" class="text-center py-3">Tidak ada hasil</td>
                    </tr>
                </tbody>
            @endforelse

            <tfoot>
                <tr>
                    <td colspan="4" class="text-center">
                        <strong>Total</strong>
                    </td>

                    <td class="text-center">
                        {{ $invoices->map(function($item) {
                            return abs($item->invoice_details->sum('quantity'));
                        })->sum() }}
                    </td>

                    <td class="text-right">
                        @money($invoices->sum('nominal'))
                    </td>

                    <td class="text-right">
                        @money($invoices->sum('nominal'))
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

@push('styles')
<style>
.table-invoices tbody:hover tr td {
    background-color: #f4f4f4;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.0/dist/index.umd.min.js"></script>

<script>
(function($) {
    $(function() {
        var picker = new easepick.create({
            element: $('#date').get(0),
            css: [
                'https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.0/dist/index.css',
            ],
            plugins: ['RangePlugin', 'LockPlugin'],
            RangePlugin: {
                tooltip: true,
            },
            LockPlugin: {
                maxDate: new Date(),
            },
        });

        picker.on('select', function(e) {
            $('#date').trigger('change');
            $('.date-clear').show();
        });

        $('.date-clear').on('click', function(e) {
            e.preventDefault();

            picker.clear();
            $(e.currentTarget).hide();
        });

        // Export
        var exportField = $('form').find('[name="export"]');

        $('.export-btn').on('click', function(e) {
            e.preventDefault();

            var el = $(e.currentTarget);
            var exportType = el.data('export');

            exportType && exportField.val(exportType).closest('form').trigger('submit');

            setTimeout(() => {
                exportField.val('');
            }, 100);
        });
    });
})(jQuery);
</script>
@endpush
