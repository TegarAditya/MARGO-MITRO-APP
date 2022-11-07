@extends('layouts.admin')
@section('content')
@php
$bahan_cat = $categories->where('slug', 'bahan')->first();
$bahan_products = $products->whereIn('category_id', [$bahan_cat->id, ...$bahan_cat->child()->pluck('id')]);

$buku_cat = $categories->where('slug', 'buku')->first();
$buku_products = $products->whereIn('category_id', [$buku_cat->id, ...$buku_cat->child()->pluck('id')]);
@endphp

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} Order Cetak
    </div>

    <div class="card-body">
        <div class="row">
            {{-- <div class="col">
                <a class="btn btn-default" href="{{ route('admin.orders.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div> --}}

            <div class="col">
                <a class="btn btn-default" href="{{ url()->previous() }}">
                    Back
                </a>
            </div>

            <div class="col-auto">
                <a class="btn btn-info" href="{{ route('admin.production-orders.edit', $productionOrder->id) }}">
                    Edit Finishing Order
                </a>
            </div>
        </div>

        <div class="model-detail mt-3">
            <h5>PO #{{ $productionOrder->po_number }}</h5>

            <div class="breadcrumb-nav">
                <ul class="m-0 border-bottom">
                    <li><a href="#modelDetail" class="active">Detail Order</a></li>
                    <li><a href="#modelProduct">Daftar Produk</a></li>
                    <li><a href="#modelBahan">Daftar Bahan</a></li>
                    <li><a href="#modelRealisasi">Realisasi</a></li>
                    <li><a href="#modelTagihan">Ringkasan</a></li>
                </ul>
            </div>

            {{-- Detail Order --}}
            <section class="py-3" id="modelDetail">
                <h6>Detail Order</h6>

                <table class="table table-sm border m-0">
                    <tbody>
                        <tr>
                            <th width="150">
                                PO Number
                            </th>
                            <td>
                                {{ $productionOrder->po_number }}
                            </td>
                        </tr>
                        <tr>
                            <th width="150">
                                No. SPK
                            </th>
                            <td>
                                {{ $productionOrder->no_spk }}
                                <a href="{{ route('admin.production-orders.show', [
                                    'production_order' => $productionOrder->id,
                                    'print' => 'spk'
                                ]) }}" target="_blank" title="Cetak SPK" class="btn btn-sm btn-default border py-0 px-1">
                                    <i class="fa fa-print text-info"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th width="150">
                                No. Kwitansi
                            </th>
                            <td>
                                {{ $productionOrder->no_kwitansi }}
                                <a href="{{ route('admin.production-orders.show', [
                                    'production_order' => $productionOrder->id,
                                    'print' => 'kwitansi'
                                ]) }}" target="_blank" title="Cetak Kwitansi" class="btn btn-sm btn-default border py-0 px-1">
                                    <i class="fa fa-print text-info"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Tanggal
                            </th>
                            <td>
                                {{ $productionOrder->date }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Jenis
                            </th>
                            <td>
                                @switch($productionOrder->type)
                                    @case('finishing')
                                        Finishing
                                        @break
                                    @case('percetakan')
                                        Percetakan
                                        @break
                                @endswitch
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Production Person
                            </th>
                            <td>
                                {{ $productionOrder->productionperson->name ?? '' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </section>

            @foreach ([
                [
                    'label' => 'Daftar Produk',
                    'product_ids' => $buku_products->pluck('id'),
                    'id' => 'modelProduct',
                ], [
                    'label' => 'Daftar Bahan',
                    'product_ids' => $bahan_products->pluck('id'),
                    'id' => 'modelBahan',
                ],
            ] as $item)
                @php
                $po_details = $productionOrder->production_order_details->whereIn('product_id', $item['product_ids']);
                @endphp
                <section class="border-top py-3" id="{{ $item['id']}}">
                    <h6 class="mb-0">{{ $item['label'] }}</h6>

                    <p class="mb-2">Total pemesanan: {{ $productionOrder->production_order_details->count() }} produk</p>

                    @foreach ($po_details as $po_detail)
                        @php
                        $product = $po_detail->product;
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
                                                Order Qty: {{ $po_detail->order_qty }}
                                            </p>

                                            <p class="mb-0 text-sm">
                                                Production Qty: {{ $po_detail->prod_qty }}
                                            </p>
                                        </div>

                                        <div class="col text-right" style="max-width: 240px">
                                            <p class="text-sm mb-0">Harga</p>
                                            <p class="m-0">@money($po_detail->ongkos_satuan)</p>
                                        </div>

                                        <div class="col text-right">
                                            <p class="text-sm mb-0">Subtotal</p>
                                            <p class="m-0">@money($po_detail->ongkos_total)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="border-top mt-2 pt-2 text-right ml-5">
                        <p class="m-0">Grand Total</p>
                        <h5 class="m-0">@money($po_details->sum('ongkos_total'))</h5>
                    </div>
                </section>
            @endforeach

            {{-- Realisasi --}}
            <section class="border-top py-3" id="modelRealisasi">
                <div class="row mb-2">
                    <div class="col">
                        <h6>Daftar Realisasi</h6>

                        <p class="mb-0">Total invoice: {{ $productionOrder->realisasis->count() }}</p>
                    </div>

                    <div class="col-auto">
                        <a href="{{ route('admin.realisasis.create', ['production_order_id'=>$productionOrder->id]) }}" class="btn btn-sm btn-success">Tambah Realisasi</a>
                    </div>
                </div>

                @foreach ($productionOrder->realisasis as $realisasi)
                    <div class="card">
                        <div class="card-body px-3 py-2">
                            <div class="row">
                                <div class="col-6 mb-1"></div>

                                <div class="col-6 text-right">
                                    <a href="{{ route('admin.realisasis.edit', $realisasi->id) }}" class="border-bottom">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                </div>

                                <div class="col-3">
                                    <p class="mb-0 text-sm">
                                        No. Realisasi
                                        <br />
                                        <strong>{{ $realisasi->no_realisasi }} |
                                        @if ($realisasi->lunas)
                                            <span class="text-success">Sudah Dibayar</span>
                                        @else
                                            <span class="text-danger">Belum Dibayar</span>
                                        @endif
                                        </strong>
                                    </p>
                                </div>

                                <div class="col text-right">
                                    <span>Tanggal<br />{{ $realisasi->date }}</span>
                                </div>
                            </div>

                            @foreach ([
                                [
                                    'label' => 'Realisasi Produk',
                                    'product_ids' => $buku_products->pluck('id'),
                                ], [
                                    'label' => 'Realisasi Bahan',
                                    'product_ids' => $bahan_products->pluck('id'),
                                ],
                            ] as $item)
                                <div class="realisasi-group mt-2">
                                    <p class="mb-1">
                                        <strong>{{ $item['label'] }}</strong>
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
                                            @forelse ($realisasi->realisasi_details->whereIn('product_id', $item['product_ids']) as $detail)
                                                @php
                                                $product = $detail->product;
                                                @endphp
                                                <tr>
                                                    <td class="text-right px-3">{{ $loop->iteration }}.</td>
                                                    <td>{{ $product->name }}</td>
                                                    <td class="text-right px-3">@money(abs($detail->price))</td>
                                                    <td class="text-center px-3">{{ abs($detail->qty) }}</td>
                                                    <td class="text-right px-3">@money(abs($detail->total))</td>
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
                                                    <strong>@money(abs($realisasi->nominal))</strong>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="border-top mt-2 pt-2 text-right ml-5">
                    <p class="m-0">Total Realisai</p>
                    <h5 class="m-0">@money($productionOrder->realisasis->sum('nominal'))</h5>
                </div>
            </section>

            {{-- Tagihan & Pembayaran --}}
            <section class="border-top py-3" id="modelTagihan">
                <div class="row text-right">
                    <div class="col text-left">
                        <h6 class="m-0">Detail Tagihan</h6>
                    </div>

                    <div class="col-auto">
                        <p class="mb-0">
                            <span>Total Order</span>
                            <br />
                            <span class="h5 mb-0 tagihan-total">@money(data_get($productionOrder, 'total', 0))</span>
                        </p>
                    </div>

                    <div class="col-auto">
                        <p class="mb-0">
                            <span>Total Realisasi</span>
                            <br />
                            <span class="h5 mb-0 tagihan-total">@money($productionOrder->realisasis->sum('nominal'))</span>
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

            console.log("ADOAKD", href, target);
        });
    });
})(jQuery);
</script>
@endpush
