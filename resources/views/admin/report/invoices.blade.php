@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        History Pengiriman
    </div>

    <div class="card-body">
        <form action="" method="POST">
            @csrf

            <div class="row">
                <div class="col row">
                    <div class="col-3">
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
                    </div>

                    <div class="col-3">
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

                    <div class="col-3">
                        <div class="form-group mb-0">
                            <label class="small mb-0" for="product_id">Produk</label>
                            <select class="form-control select2 {{ $errors->has('product_id') ? 'is-invalid' : '' }}" name="product_id" id="product_id">
                                <option value="">Semua Produk</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ (old('product_id') ? old('product_id') : (request('product_id') == $product->id ? 'selected' : '')) }}>{{ $product->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('product_id'))
                                <span class="text-danger">{{ $errors->first('product_id') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-3">
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

        <table class="mt-4 table table-bordered table-invoices">
            <thead>
                <tr>
                    <th rowspan="2"></th>
                    <th rowspan="2">
                        {{ trans('cruds.invoice.fields.order') }}
                    </th>
                    <th width="110" rowspan="2">
                        {{ trans('cruds.invoice.fields.date') }}
                    </th>
                    <th colspan="3" class="text-center">
                        Products
                    </th>
                    <th rowspan="2">
                        {{ trans('cruds.invoice.fields.nominal') }}
                    </th>
                </tr>

                <tr>
                    <th width="150">Name</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
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
                $is_out = 0 < $invoice->nominal;
                $no = $loop->iteration;
                @endphp

                <tbody>
                    @foreach ($invoice->invoice_details as $detail)
                        <tr>
                            @if ($loop->first)
                                <td rowspan="{{ $rowspan }}">{{ $no }}</td>
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

                                    <div class="row pt-1 mt-1 border-top">
                                        <div class="col-12">
                                            <p class="pt-3 text-center mb-0 {{ $is_out ? 'text-success' : 'text-danger' }}">
                                                <strong class="text-xs">
                                                    @if ($is_out)
                                                        <i class="fa fa-arrow-up"></i>
                                                        Keluar
                                                    @else
                                                        <i class="fa fa-arrow-down"></i>
                                                        Masuk
                                                    @endif
                                                </strong>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td rowspan="{{ $rowspan }}">
                                    {{ $invoice->date }}
                                </td>
                            @endif

                            <td>
                                @if ($product = $detail->product)
                                    <p class="text-sm m-0">
                                        <span>{{ $product->name }}</span>
                                        <br />
                                        <span class="text-xs text-muted">@money($detail->price)</span>
                                    </p>
                                @else
                                    <p class="m-0">Produk</p>
                                @endif
                            </td>
                            <td class="text-center">{{ abs($detail->quantity) }}</td>
                            <td class="text-right">@money(abs($detail->total))</td>

                            @if ($loop->first)
                                <td rowspan="{{ $rowspan }}" class="text-right">@money(abs($invoice->nominal))</td>
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
                    <td colspan="4" class="text-right">
                        <strong>Total</strong>
                    </td>

                    <td class="text-right">
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

        console.log("PCIKER", picker);
    });
})(jQuery);
</script>
@endpush
