@php
/**
 * Item Product
 *
 * @var $detail App\Models\OrderDetail
 */

$product = $detail->product ?: new App\Models\Product;
$category = $product->category;
$cover = $product->brand;
$isi = $product->isi;
$jenjang = $product->jenjang;

$stock = $product->stock ?: 0;

$foto = !$product->foto ? null : $product->foto->first();

$modal = !isset($modal) ? '#productModal' : $modal;
$name = !isset($name) ? 'products' : $name;
@endphp
<div class="item-product row" data-id="{{ $product->id }}" data-name="{{ $name }}">
    <div class="col-8 row">
        <div class="col product-col-main {{ !$product->id ? 'align-self-center' : '' }}">
            @if ($product->id)
                <div class="product-content">
                    <h6 class="text-sm product-name mb-1">{{ $product->nama_buku }}</h6>

                    <p class="mb-0 text-sm">
                        HPP: <span class="product-hpp">@money($product->hpp)</span>
                    </p>

                    <p class="mb-0 text-sm">
                        Cover - Isi : <span class="product-category">{{ !$cover ? '' : $cover->name }} - {{ !$isi ? '' : $isi->name }}</span>
                    </p>

                    <p class="mb-0 text-sm">
                        Jenjang: <span class="product-category">{{ !$jenjang ? '' : $jenjang->name }}</span>
                    </p>

                    <p class="mb-0 text-sm">
                        Stock: <span class="product-stock">{{ $product->stock }}</span>
                    </p>
                </div>
            @else
                <button type="button" class="btn py-1 border product-pick" data-toggle="modal" data-target="{{ $modal }}">
                    <span class="text-sm">Pilih Produk</span>

                    <i class="fa fa-chevron-down text-xs ml-4"></i>
                </button>
            @endif
        </div>
    </div>

    <div class="col product-col-content row align-items-end align-self-center">
        <div class="col" style="max-width: 200px">
            <p class="mb-0 text-sm">Quantity</p>

            <x-admin.form-group
                type="number"
                id="fieldQty-{{ $product->id }}"
                :name="!$product->id ? null : $name.'['.$product->id.'][qty]'"
                containerClass=" m-0"
                boxClass=" p-0"
                class="form-control-sm hide-arrows text-center product-qty product-qty1"
                value="{{ $detail->quantity ? abs($detail->quantity) : 0 }}"
                min="0"
            >
                <x-slot name="left">
                    <button type="button" class="btn btn-sm border-0 px-2 product-qty-act" data-target=".product-qty1" data-action="-">
                        &minus;
                    </button>
                </x-slot>

                <x-slot name="right">
                    <button type="button" class="btn btn-sm border-0 px-2 product-qty-act" data-target=".product-qty1" data-action="+">
                        &plus;
                    </button>
                </x-slot>
            </x-admin.form-group>
        </div>

        <div class="col-auto pl-5 item-product-action">
            <a href="#" class="btn btn-danger btn-sm product-delete">
                <i class="fa fa-trash"></i>
            </a>
        </div>
    </div>
</div>
