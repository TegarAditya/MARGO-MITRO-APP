@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.order.title') }}
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col">
                <a class="btn btn-default" href="{{ route('admin.orders.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>

            <div class="col-auto">
                <a class="btn btn-info" href="{{ route('admin.orders.edit', $order->id) }}">
                    Edit Order
                </a>
            </div>
        </div>

        <div class="model-detail mt-3">
            <h5>Order #{{ $order->no_order }}</h5>

            {{-- Detail Order --}}
            <section class="mt-3" id="modelDetail">
                <h6>Detail Order</h6>

                <table class="table table-sm border">
                    <tbody>
                        <tr>
                            <th width="150">
                                No. Order
                            </th>
                            <td>
                                {{ $order->no_order }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.date') }}
                            </th>
                            <td>
                                {{ $order->date }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.salesperson') }}
                            </th>
                            <td>
                                {{ $order->salesperson->name ?? '' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </section>

            {{-- Daftar Order --}}
            <section class="mt-3" id="modelProduct">
                <h6 class="mb-0">Daftar Produk</h6>

                <p class="mb-2">Total pemesanan {{ $order->order_details->count() }} produk</p>

                @foreach ($order->order_details as $order_detail)
                    @php
                    $product = $order_detail->product;
                    $category = $product->category;

                    $stock = $product->stock ?: 0;
                    @endphp

                    <div class="card">
                        <div class="card-body px-3 py-2">
                            <h6 class="text-sm product-name mb-0">{{ $product->name }}</h6>

                            <p class="mb-2 text-sm">
                                Category: {{ !$category ? 'Tidak ada' : $category->name }}
                            </p>

                            <div class="row">
                                @if ($product->foto && $foto = $product->foto->first())
                                    <div class="col-auto">
                                        <img src="{{ $foto->getUrl('thumb') }}" class="product-img" />
                                    </div>
                                @endif
                            
                                <div class="col-4">
                                    <p class="mb-0 text-sm">
                                        Stock: {{ $product->stock }}
                                    </p>

                                    <p class="mb-0 text-sm">
                                        HPP: @money($product->hpp)
                                    </p>
                                </div>

                                <div class="col row align-items-end align-self-center">
                                    <div class="col" style="max-width: 120px">
                                        <p class="mb-0 text-sm">
                                            Order Qty: {{ $order_detail->quantity }}
                                        </p>

                                        <p class="mb-0 text-sm">
                                            Terkirim: {{ $order_detail->moved }}
                                        </p>
                                    </div>
                            
                                    <div class="col text-right" style="max-width: 240px">
                                        <p class="text-sm mb-0">Harga</p>
                                        <p class="m-0">@money($order_detail->price)</p>
                                    </div>
                            
                                    <div class="col text-right">
                                        <p class="text-sm mb-0">Subtotal</p>
                                        <p class="m-0">@money($order_detail->total)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="border-top mt-2 pt-2 text-right">
                    <p class="m-0">Grand Total</p>
                    <h5 class="m-0">@money($order->tagihan->total)</h5>
                </div>
            </section>

            {{-- Invoice --}}
            <section class="mt-3" id="modelInvoice">
                <h6>Daftar Invoice &amp; Surat Jalan</h6>

                <p class="mb-2">Total invoice {{ $order->invoices->count() }}</p>

                @foreach ($order->invoices as $invoice)
                    @php
                    $print = function($type) use ($invoice) {
                        return route('admin.invoices.show', ['invoice' => $invoice->id, 'print' => $type]);
                    };
                    @endphp
                    <div class="card">
                        <div class="card-body px-3 py-2">
                            <div class="row">
                                <div class="col-6 mb-1">
                                    <span class="badge badge-{{ 'Keluar' == $invoice->type ? 'warning' : 'info' }}">{{ $invoice->type }}</span>
                                </div>

                                <div class="col-6 text-right">
                                    <a href="{{ route('admin.invoices.edit', $invoice->id) }}" class="border-bottom">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                </div>

                                <div class="col-3">
                                    <p class="mb-0 text-sm">
                                        No. Invoice
                                        <br />
                                        <strong>{{ $invoice->no_invoice }}</strong>

                                        <a href="{{ $print('inv') }}" class="fa fa-print ml-1 text-info" title="Print Invoice" target="_blank"></a>
                                    </p>
                                </div>

                                <div class="col-3">
                                    <p class="mb-0 text-sm">
                                        No. Surat Jalan
                                        <br />
                                        <strong>{{ $invoice->no_suratjalan }}</strong>

                                        <a href="{{ $print('sj') }}" class="fa fa-print ml-1 text-info" title="Print Surat Jalan" target="_blank"></a>
                                    </p>
                                </div>

                                <div class="col text-right">
                                    <span>Tanggal<br />{{ $invoice->date }}</span>
                                </div>
                            </div>

                            <p class="mt-2 mb-1">
                                <strong>Produk {{ $invoice->type }}</strong>
                            </p>

                            <table class="table table-sm table-bordered m-0">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="1%">No.</th>
                                        <th>Nama</th>
                                        <th class="text-center px-3" width="1%">Harga</th>
                                        <th class="text-center px-3" width="1%">Qty</th>
                                        <th class="text-center px-3" width="1%">Subtotal</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($invoice->invoice_details as $invoice_detail)
                                        @php
                                        $product = $invoice_detail->product;
                                        @endphp
                                        <tr>
                                            <td class="text-right px-3">{{ $loop->iteration }}.</td>
                                            <td>{{ $product->name }}</td>
                                            <td class="text-right px-3">@money($invoice_detail->price)</td>
                                            <td class="text-center px-3">{{ $invoice_detail->quantity }}</td>
                                            <td class="text-right px-3">@money($invoice_detail->total)</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="px-3" colspan="5">Tidak ada produk</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td class="text-right px-3" colspan="4">Total</td>
                                        <td class="text-right px-3">
                                            <strong>@money($invoice->nominal)</strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @endforeach

                <div class="border-top mt-2 pt-2 text-right">
                    <p class="m-0">Total Invoice</p>
                    <h5 class="m-0">@money($order->invoices->sum('nominal'))</h5>
                </div>
            </section>

            {{-- Pembayaran --}}
            <section class="mt-3" id="modelPembayaran">
                <h6>Daftar Pembayaran</h6>

            </section>
        </div>
    </div>
</div>
@endsection
