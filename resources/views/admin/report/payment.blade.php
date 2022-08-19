@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        History Pembayaran
    </div>

    <div class="card-body">
        <form action="" method="POST">
            @csrf

            <input type="hidden" name="export" value="{{ request('export') }}" />

            <div class="row">
                <div class="col row">
                    <div class="col-4">
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
                    <th>{{ trans('cruds.invoice.fields.order') }}</th>
                    <th>Tagihan</th>
                    <th width="110">{{ trans('cruds.invoice.fields.date') }}</th>
                    <th>{{ trans('cruds.invoice.fields.nominal') }}</th>
                    <th>Diskon</th>
                    <th>Bayar</th>
                </tr>
            </thead>

            @forelse ($pembayarans as $pembayaran)
                @php
                $order = $pembayaran->order;
                @endphp

                <tbody>
                    <tr>
                        <td class="align-middle text-center">{{ $loop->iteration }}</td>
                        <td>
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
                                        <strong class="text-xs">No. Kwitansi</strong>
                                        <br />
                                        <a href="{{ route('admin.pembayarans.edit', $pembayaran->id) }}">{{ $pembayaran->no_kwitansi }}</a>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-0">
                                        <strong class="text-xs">Total Order</strong>
                                        <br />
                                        @money(data_get($order, 'tagihan.total', 0))
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="row">
                                <div class="col-6 text-right">
                                    <p class="mb-0">
                                        <strong class="text-xs">Total Tagihan</strong>
                                        <br />
                                        @money($order->invoices->sum('nominal'))
                                    </p>
                                </div>
                                <div class="col-6 text-right">
                                    <p class="mb-0">
                                        <strong class="text-xs">Sisa Tagihan</strong>
                                        <br />
                                        @money($order->sisa_tagihan)
                                    </p>
                                </div>
                            </div>
                            <div class="row pt-1 mt-1 border-top">
                                <div class="col-12 text-right">
                                    <p class="mb-0">
                                        <strong class="text-xs">Total Pembayaran</strong>
                                        <br />
                                        @money($order->pembayarans->sum('nominal'))
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="text-center align-middle">
                            {{ $pembayaran->tanggal }}
                        </td>
                        <td class="text-right align-middle">@money(abs($pembayaran->nominal))</td>
                        <td class="text-right align-middle">
                            @if (!$pembayaran->diskon)
                                <span>-</span>
                            @else
                                <span>@money(abs($pembayaran->diskon))</span>
                            @endif
                        </td>
                        <td class="text-right align-middle">@money(abs($pembayaran->bayar))</td>
                    </tr>
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

                    <td class="text-right">
                        @money($pembayarans->sum('nominal'))
                    </td>

                    <td class="text-right">
                        @money($pembayarans->sum('diskon'))
                    </td>

                    <td class="text-right">
                        @money($pembayarans->sum('bayar'))
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
