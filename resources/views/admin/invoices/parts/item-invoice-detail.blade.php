@php
/**
 * Item Product
 * 
 * @var $detail App\Models\InvoiceDetail
 */

$product = $detail->product ?: new App\Models\Product;
$category = $product->category;

$qtyMax = $detail->quantity - $detail->moved;
@endphp
<div class="item-product row" data-id="{{ $product->id }}" data-price="{{ $detail->price }}" data-moved="{{ $detail->moved }}" data-max="{{ $qtyMax }}" data-qty="{{ $detail->quantity }}">
    <div class="col-auto" style="display: {{ !$product->id ? 'none' : 'block' }}">
        @if ($product->foto && $foto = $product->foto->first())
            <img src="{{ $foto->getUrl('thumb') }}" class="product-img" />
        @else
            <img src="" class="product-img" />
        @endif
    </div>

    <div class="col-4">
        <h5 class="product-name mb-1">{{ $product->name }}</h5>

        <p class="mb-0 text-sm">
            Quantity: <span class="product-qty-max">{{ $detail->quantity }}</span>
        </p>

        @if (!$detail->id)
            <p class="mb-0 text-sm">
                Terkirim: <span class="product-moved">{{ $detail->moved }}</span>
            </p>
        @endif
    </div>

    <div class="col row align-items-end align-self-center">
        <div class="col" style="max-width: 120px">
            <p class="mb-0 text-sm">Qty Kirim</p>

            <x-admin.form-group
                type="number"
                id="fieldQty-{{ $product->id }}"
                name="products[{{ $product->id ?: 0 }}][qty]"
                containerClass=" m-0"
                boxClass=" p-0"
                class="form-control-sm hide-arrows text-center product-qty"
                value="{{ $detail->quantity }}"
                min="{{ $detail->quantity ?: 0 }}"
                max="{{ $qtyMax }}"
                :readonly="$detail->id"
            >
                <x-slot name="left">
                    <button type="button" class="btn btn-sm border-0 px-2 product-qty-act" data-action="-">
                        &minus;
                    </button>
                </x-slot>

                <x-slot name="right">
                    <button type="button" class="btn btn-sm border-0 px-2 product-qty-act" data-action="+">
                        &plus;
                    </button>
                </x-slot>
            </x-admin.form-group>
        </div>

        <div class="col" style="max-width: 240px">
            <p class="mb-0 text-sm">Harga</p>

            <x-admin.form-group
                type="number"
                id="fieldPrice-{{ $product->id }}"
                name="products[{{ $product->id ?: 0 }}][price]"
                containerClass=" m-0"
                boxClass=" px-2 py-0"
                class="form-control-sm product-price"
                value="{{ $detail->price }}"
                min="1"
                :readonly="$detail->id"
            >
                <x-slot name="left">
                    <span class="text-sm mr-1">Rp</span>
                </x-slot>
            </x-admin.form-group>
        </div>

        <div class="col text-right">
            <p class="text-sm mb-0">Subtotal</p>
            <p class="m-0 product-subtotal">Rp{{ number_format($detail->total) }}</p>
        </div>

        @if (!$detail->id)
            <div class="col-auto pl-5 item-product-action">
                <a href="#" class="btn btn-danger btn-sm product-delete">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
        @endif
    </div>
</div>
