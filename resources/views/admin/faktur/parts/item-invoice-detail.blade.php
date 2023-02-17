@php
/**
 * Item Product
 *
 * @var $detail App\Models\InvoiceDetail
 */

$product = $detail->product ?: new App\Models\Product;
$category = $product->category;
$cover = $product->brand;
$isi = $product->isi;
$jenjang = $product->jenjang;

$order_detail = $detail->order_detail ?: null;
$qtyMax = !$order_detail ? $product->stock : $order_detail->quantity;

if ($order_detail) {
    $bonus = $order_detail->bonus ?: null;
    $productBonus = !$bonus ? null : $bonus->product;
    $maxBonus = !$bonus ? 0 : $bonus->quantity;
} else {
    $bonus = null;
    $productBonus = null;
    $maxBonus = 1;
}

$foto = !$product->foto ? null : $product->foto->first();

$modal = !isset($modal) ? '#productModal' : $modal;
$name = !isset($name) ? 'products' : $name;
@endphp
<div class="item-product row" data-id="{{ $product->id }}" data-jenjang="{{ $product->jenjang_id }}" data-price="{{ $detail->price }}" data-moved="{{ $order_detail->moved ?? 0 }}" data-max="{{ $qtyMax }}"
    data-qty="{{ $detail->quantity }}"
    data-stock="{{ $product->stock }}"
    data-name="{{ $name }}"
    @if($bonus)
        data-pg="{{ $bonus->product->id }}"
        data-pgstock="{{ $bonus->product->stock }}"
        data-pgqty="{{ $bonus->quantity }}"
        data-pgmoved="{{ $bonus->moved }}"
        data-pgmax="{{ $bonus->quantity }}"
    @endif
    >
    <div class="col-7 row">
        <div class="col-auto" style="display: {{ (!$product->id || !$foto) ? 'none' : 'block' }}">
            @if ($foto)
                <img src="{{ $foto->getUrl('thumb') }}" class="product-img" />
            @elseif (!$product->id)
                <img src="" class="product-img" />
            @endif
        </div>

        <div class="col product-col-main {{ !$product->id ? 'align-self-center' : '' }}">
            @if ($product->id)
                <div class="product-content">
                    <h6 class="text-sm product-name mb-1">{{ $product->nama_buku }}</h6>

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
        <div class="col" style="max-width: 180px">
            <p class="mb-0 text-sm">Quantity</p>

            <x-admin.form-group
                type="number"
                id="fieldQty-{{ $product->id }}"
                :name="!$product->id ? null : $name.'['.($product->id ?: 0).'][qty]'"
                containerClass=" m-0"
                boxClass=" p-0"
                class="form-control-sm hide-arrows text-center product-qty product-qty1"
                value="{{ abs($detail->quantity) }}"
                :min="0"
                max="{{ $qtyMax }}"
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

        <div class="col div-product-pg" style="max-width: 260px; display : {{ !$bonus ? 'none' : 'block' }};">
            <div class="product-pg text-center">
                <h6 class="text-sm product-name mb-1">Kelengkapan</h6>

                <p class="mb-0 text-sm">
                    Stock: <span class="product-stock">{{ $productBonus->stock ?? '' }}</span>
                </p>
            </div>
        </div>

        <div class="col div-product-bonus" style="max-width: 150px; display : {{ !$bonus ? 'none' : 'block' }};">
            <p class="mb-0 text-sm">Quantity</p>

            <x-admin.form-group
                type="number"
                id="fieldBonus-{{ $product->id }}"
                name="{{ (!$bonus? null: $name).'['.$product->id.'][bonus]' }}"
                containerClass=" m-0"
                boxClass=" p-0"
                class="form-control-sm hide-arrows text-center product-bonus product-bonus1"
                value="{{ !$bonus? 0 : (!isset($detail->bonus) ? 0 :  abs($detail->bonus->quantity)) }}"
                min="0"
                max="{{ $maxBonus }}"
            >
                <x-slot name="left">
                    <button type="button" class="btn btn-sm border-0 px-2 product-bonus-act" data-target=".product-bonus1" data-action="-">
                        &minus;
                    </button>
                </x-slot>

                <x-slot name="right">
                    <button type="button" class="btn btn-sm border-0 px-2 product-bonus-act" data-target=".product-bonus1" data-action="+">
                        &plus;
                    </button>
                </x-slot>
            </x-admin.form-group>
        </div>

        <input
                type="hidden"
                class="product-price"
                name="{{ !$product->id ? null : $name.'['.$product->id.'][price]' }}"
                value="{{ $detail->price ?: 0 }}"
            />
    </div>
</div>
