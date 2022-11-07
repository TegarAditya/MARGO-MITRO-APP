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
                    <div class="col-3">
                        <div class="form-group mb-0">
                            <label class="small mb-0" for="finishing_order_id">Production Order</label>
                            <select class="form-control select2 {{ $errors->has('finishing_order_id') ? 'is-invalid' : '' }}" name="finishing_order_id" id="finishing_order_id">
                                <option value="">Semua Order</option>
                                @foreach($finishingOrders as $finishingOrder)
                                    <option value="{{ $finishingOrder->id }}" {{ (old('finishing_order_id') ? old('finishing_order_id') : (request('finishing_order_id') == $finishingOrder->id ? 'selected' : '')) }}>{{ $finishingOrder->po_number }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('finishing_order_id'))
                                <span class="text-danger">{{ $errors->first('finishing_order_id') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="form-group mb-0">
                            <label class="small mb-0" for="productionperson_id">Production Person</label>
                            <select class="form-control select2 {{ $errors->has('productionperson_id') ? 'is-invalid' : '' }}" name="productionperson_id" id="productionperson_id">
                                <option value="">Semua Production Person</option>
                                @foreach($productionpeople as $person)
                                    <option value="{{ $person->id }}" {{ (old('productionperson_id') ? old('productionperson_id') : (request('productionperson_id') == $person->id ? 'selected' : '')) }}>{{ $person->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('productionperson_id'))
                                <span class="text-danger">{{ $errors->first('productionperson_id') }}</span>
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
                    <th rowspan="2"></th>
                    <th rowspan="2" class="align-middle">
                        Production Order
                    </th>
                    <th width="110" rowspan="2" class="align-middle">
                        Tanggal
                    </th>
                    <th colspan="3" class="text-center">
                        Products
                    </th>
                    <th rowspan="2" class="align-middle">
                        Nominal
                    </th>
                </tr>

                <tr>
                    <th width="150">Name</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>

            @forelse ($realisasis as $realisasi)
                @php
                $finishingOrder = $realisasi->finishing_order;
                $rowspan = $realisasi->realisasi_details->count();
                $link = route('admin.realisasis.edit', $realisasi->id);
                $print = function($type) use ($realisasi) {
                    return route('admin.realisasis.show', ['realisasi' => $realisasi->id, 'print' => $type]);
                };
                $type = ucfirst($finishingOrder->type);
                $no = $loop->iteration;
                @endphp

                <tbody>
                    @foreach ($realisasi->realisasi_details as $detail)
                        <tr>
                            @if ($loop->first)
                                <td rowspan="{{ $rowspan }}" class="align-middle text-center">{{ $no }}</td>
                                <td rowspan="{{ $rowspan }}">
                                    <div class="row">
                                        <div class="col-6">
                                            <p class="mb-0">
                                                <strong class="text-xs">No. PO</strong>
                                                <br />
                                                <a href="{{ route('admin.finishing-orders.show', $finishingOrder->id) }}">{{ $finishingOrder->po_number }}</a>
                                            </p>
                                        </div>

                                        <div class="col-6">
                                            <p class="mb-0">
                                                <strong class="text-xs">Production Person</strong>
                                                <br />
                                                <span>{{ data_get($finishingOrder, 'productionperson.name', '-') }}</span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="row pt-1 mt-1 border-top">
                                        <div class="col-6">
                                            <p class="mb-0">
                                                <strong class="text-xs">No. SPK</strong>
                                                <br />
                                                <a href="{{ route('admin.finishing-orders.edit', $finishingOrder->id) }}">{{ $finishingOrder->no_spk }}</a>
                                            </p>
                                        </div>

                                        <div class="col-6">
                                            <p class="mb-0">
                                                <strong class="text-xs">No. Kwitansi</strong>
                                                <br />
                                                <a href="{{ route('admin.finishing-orders.edit', $finishingOrder->id) }}">{{ $finishingOrder->no_kwitansi }}</a>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="row pt-1 mt-1 border-top">
                                        <div class="col-12">
                                            <p class="pt-3 text-center mb-0 {{ $finishingOrder->type === 'percetakan' ? 'text-info' : 'text-success' }}">
                                                <strong class="text-xs">{{ $type }}</strong>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td rowspan="{{ $rowspan }}" class="align-middle text-center">
                                    {{ $realisasi->date }}
                                </td>
                            @endif

                            <td class="align-middle">
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
                            <td class="text-center align-middle">{{ abs($detail->qty) }}</td>
                            <td class="text-right align-middle">@money(abs($detail->total))</td>

                            @if ($loop->first)
                                <td rowspan="{{ $rowspan }}" class="align-middle text-right">@money(abs($realisasi->nominal))</td>
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

                    <td class="text-right">
                        {{ $realisasis->map(function($item) {
                            return abs($item->realisasi_details->sum('quantity'));
                        })->sum() }}
                    </td>

                    <td class="text-right">
                        @money($realisasis->sum('nominal'))
                    </td>

                    <td class="text-right">
                        @money($realisasis->sum('nominal'))
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
