@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.order.title') }}
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col">
                <a class="btn btn-default" href="{{ url()->previous() }}">
                    Back
                </a>
            </div>

            <div class="col-auto">
                <a class="btn btn-info" href="{{ route('admin.stock-adjustments.edit', $order->id) }}">
                    Edit Adjustment
                </a>
            </div>
        </div>

        <div class="model-detail mt-3">
            <h5>Order #{{ $order->no_order }}</h5>

            <div class="breadcrumb-nav">
                <ul class="m-0 border-bottom">
                    <li><a href="#modelDetail" class="active">Detail Order</a></li>
                    <li><a href="#modelProduct">Daftar Produk</a></li>
                    <li><a href="#modelInvoice">Invoice &amp; Faktur</a></li>
                    <li><a href="#modelTagihan">Pembayaran</a></li>
                </ul>
            </div>

            {{-- Detail Order --}}
            <section class="py-3" id="modelDetail">
                <h6>Detail Order</h6>

                <table class="table table-sm border m-0">
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
            <section class="border-top py-3" id="modelProduct">
                <h6 class="mb-0">Daftar Produk</h6>

                <p class="mb-2">Total pemesanan: {{ $order->order_details->count() }} produk</p>

                @foreach ($order->order_details as $order_detail)
                    @php
                    $product = $order_detail->product;
                    $category = $product->category;
                    $jenjang = $product->jenjang;

                    $stock = $product->stock ?: 0;
                    @endphp

                    <div class="card">
                        <div class="card-body px-3 py-2">
                            <h6 class="text-sm product-name mb-0">{{ $product->nama_isi_buku }}</h6>

                            <p class="mb-2 text-sm">
                                {{-- Category: {{ !$category ? 'Tidak ada' : $category->name }} --}}
                                Jenjang: {{ !$jenjang ? 'Tidak ada' : $jenjang->name }}
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
                                            Pesanan: {{ $order_detail->quantity }}
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

                <div class="border-top mt-2 pt-2 text-right ml-5">
                    <p class="m-0">Grand Total</p>
                    <h5 class="m-0">@money($order->tagihan->total)</h5>
                </div>
            </section>

            {{-- Invoice --}}
            <section class="border-top py-3" id="modelInvoice">
                <div class="row mb-2">
                    <div class="col">
                        <h6>Daftar Invoice &amp; Surat Jalan</h6>

                        <p class="mb-0">Total invoice: {{ $order->invoices->count() }}</p>
                    </div>

                    <div class="col-auto">
                        <a href="{{ route('admin.invoices.create', ['order_id'=>$order->id]) }}" class="btn btn-sm btn-success">Tambah Invoice</a>
                    </div>
                </div>

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
                                        <th>Jenjang</th>
                                        <th>Tema/Mapel</th>
                                        <th class="text-center px-3" width="15%">Harga</th>
                                        <th class="text-center px-3" width="1%">Qty</th>
                                        <th class="text-center px-3" width="20%">Subtotal</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($invoice->invoice_details as $invoice_detail)
                                        @php
                                        $product = $invoice_detail->product;
                                        @endphp
                                        <tr>
                                            <td class="text-right px-3">{{ $loop->iteration }}.</td>
                                            <td class="text-center">{{ $product->jenjang->name ?? '' }}</td>
                                            <td>{{ $product->nama_isi_buku }}</td>
                                            <td class="text-right px-3">@money(abs($invoice_detail->price))</td>
                                            <td class="text-center px-3">{{ abs($invoice_detail->quantity) }}</td>
                                            <td class="text-right px-3">@money(abs($invoice_detail->total))</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="px-3" colspan="6">Tidak ada produk</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td class="text-center px-3" colspan="5"><strong>Total</strong></td>
                                        <td class="text-right px-3">
                                            <strong>@money(abs($invoice->nominal))</strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @endforeach

                <div class="border-top mt-2 pt-2 text-right ml-5">
                    <p class="m-0">Total Invoice</p>
                    <h5 class="m-0">@money($order->invoices->sum('nominal'))</h5>
                </div>
            </section>

            {{-- Tagihan & Pembayaran --}}
            <section class="border-top py-3" id="modelTagihan">
                <div class="row mb-2">
                    <div class="col">
                        <h6>Tagihan &amp; Pembayaran</h6>

                        <p class="mb-0">Total pembayaran: {{ $order->pembayarans->count() }}</p>
                    </div>

                    <div class="col-auto">
                        <a href="{{ route('admin.pembayarans.create', ['order_id'=>$order->id,'tagihan_id'=>$order->tagihan->id]) }}" class="btn btn-sm btn-success">Tambah Pembayaran</a>
                    </div>
                </div>

                <table class="table table-bordered table-hover m-0">
                    <thead>
                        <tr>
                            <th class="text-center" width="1%">No.</th>
                            <th>No. Kwitansi</th>
                            <th class="text-center px-3" width="100">Tanggal</th>
                            <th class="text-center px-3" width="15%">Nominal</th>
                            <th class="text-center px-3" width="10%">Diskon</th>
                            <th class="text-center px-3" width="15%">Bayar</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($order->pembayarans as $pembayaran)
                            <tr>
                                <td class="text-right px-3">{{ $loop->iteration }}.</td>
                                <td>
                                    <div class="row">
                                        <div class="col">
                                            <span>{{ $pembayaran->no_kwitansi }}</span>
                                            <a href="{{ route('admin.pembayarans.show', [
                                                'pembayaran' => $pembayaran->id,
                                                'print' => 'on'
                                            ]) }}" title="Cetak Pembayaran" target="_blank" class="text-info ml-1">
                                                <i class="fa fa-print"></i>
                                            </a>
                                        </div>

                                        <div class="col-auto">
                                            <a href="{{ route('admin.pembayarans.edit', [
                                                'pembayaran' => $pembayaran->id,
                                            ]) }}" title="Edit Pembayaran" class="text-info ml-1">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $pembayaran->tanggal }}</td>
                                <td class="text-right px-3">@money($pembayaran->nominal)</td>
                                <td class="text-center px-3">
                                    @if (!$pembayaran->diskon)
                                        <span>-</span>
                                    @else
                                        <span>@money($pembayaran->diskon)</span>
                                    @endif
                                </td>
                                <td class="text-right px-3">@money($pembayaran->bayar)</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-3" colspan="5">Belum ada pembayaran</td>
                            </tr>
                        @endforelse
                    </tbody>

                    <tfoot>
                        <tr>
                            <td class="text-right px-3" colspan="3">
                                <strong>Total</strong>
                            </td>
                            <td class="text-right px-3">
                                <strong>@money($order->pembayarans->sum('nominal'))</strong>
                            </td>
                            <td class="text-right px-3">
                                <strong>@money($order->pembayarans->sum('diskon'))</strong>
                            </td>
                            <td class="text-right px-3">
                                <strong>@money($order->pembayarans->sum('bayar'))</strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <hr class="my-2 text-right ml-5 mx-0" />

                <div class="row text-right">
                    <div class="col text-left">
                        <h6 class="m-0">Detail Tagihan</h6>
                    </div>

                    <div class="col-auto">
                        <p class="mb-0">
                            <span>Total Order</span>
                            <br />
                            <span class="h5 mb-0 tagihan-total">@money(data_get($order, 'tagihan.total', 0))</span>
                        </p>
                    </div>

                    <div class="col-auto">
                        <p class="mb-0">
                            <span>Total Tagihan</span>
                            <br />
                            <span class="h5 mb-0 tagihan-total">@money($order->invoices->sum('nominal'))</span>
                        </p>
                    </div>

                    <div class="col-auto">
                        <p class="mb-0">
                            <span>Total Pembayaran</span>
                            <br />
                            <span class="h5 mb-0 tagihan-total">@money($order->pembayarans->sum('nominal'))</span>
                        </p>
                    </div>

                    <div class="col-auto">
                        <p class="mb-0">
                            <span>Sisa Tagihan</span>
                            <br />
                            <span class="h5 mb-0 tagihan-total">@money($order->sisa_tagihan)</span>
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function($) {
    $(function() {
        var maxScroll = $(document).height() - $(window).height();
        var detail = $('.model-detail');
        var nav = $('.breadcrumb-nav');
        var navHi = nav.height();
        var sections = detail.children('section');
        var tops = sections.map(function (index, item) {
            return $(item).offset().top;
        });

        $(window).on('scroll', function(e) {
            var scroll = e.currentTarget.scrollY + navHi;
            var section;

            tops.map(function(index, item) {
                if (scroll >= item) {
                    section = sections.eq(index);
                }
            });

            if (scroll >= maxScroll) {
                section = sections.eq(tops.length - 1);
            }

            if (section) {
                var id = section.attr('id');
                var navLink = nav.find('a[href="#'+id+'"]');

                nav.find('a').removeClass('active');
                navLink.length && navLink.addClass('active');
            }
        });

        nav.find('a').on('click', function(e) {
            e.preventDefault();

            var el = $(e.currentTarget);
            var href = el.attr('href');
            var target = $(href);

            target.length && $('html, body').animate({
                scrollTop: target.offset().top - nav.height()
            }, 500, 'linear');
        });
    });
})(jQuery);
</script>
@endpush
