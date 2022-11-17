@php
/**
 * Item Product
 * 
 * @var $detail App\Models\OrderDetail
 */

$product = $detail->product ?: new App\Models\Product;
$category = $product->category;

$stock = $product->stock ?: 0;
$qtyMax = !$detail->id ? $stock : ($detail->quantity + $stock);

$foto = !$product->foto ? null : $product->foto->first();

$modal = !isset($modal) ? '#productModal' : $modal;
$name = !isset($name) ? 'products' : $name;
$hidden = !isset($hidden) ? false : (bool) $hidden;
$placeholder = !isset($placeholder) ? 'Pilih Produk' : $placeholder;
@endphp
<div class="item-product row" data-id="{{ $product->id }}" data-price="{{ $detail->price ?: $product->price }}" data-hpp="{{ $product->hpp }}" data-name="{{ $name }}" style="display: {{ $hidden ? 'none' : 'flex' }}">
    <div class="col-6 product-col-info row">
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
                    <h6 class="text-sm product-name mb-1">{{ $product->name }}</h6>

                    <p class="mb-0 text-sm">
                        HPP: <span class="product-hpp">@money($product->hpp)</span>
                    </p>

                    <p class="mb-0 text-sm">
                        Category: <span class="product-category">{{ !$category ? '' : $category->name }}</span>
                    </p>

                    <p class="mb-0 text-sm">
                        Stock: <span class="product-stock">{{ $product->stock }}</span>
                    </p>
                </div>
            @else
                <button type="button" class="btn py-1 border product-pick" data-toggle="modal" data-target="{{ $modal }}">
                    <span class="text-sm">{{ $placeholder }}</span>

                    <i class="fa fa-chevron-down text-xs ml-4"></i>
                </button>
            @endif
        </div>
    </div>

    <div class="col product-col-content">
        <div class="row align-items-end align-self-center">
            <div class="col" style="max-width: 120px">
                <p class="mb-0 text-sm">Qty Order</p>

                <input
                    type="hidden"
                    class="product-group"
                    name="{{ !$product->id ? null : $name.'['.$product->id.'][group]' }}"
                    value="{{ $detail->group ?: 0 }}"
                />

                <x-admin.form-group
                    type="number"
                    id="fieldQty-{{ $product->id }}"
                    :name="!$product->id ? null : $name.'['.$product->id.'][qty]'"
                    containerClass=" m-0"
                    boxClass=" p-0"
                    class="form-control-sm hide-arrows text-center product-qty product-qty1"
                    value="{{ $detail->quantity ?: 0 }}"
                    min="0"
                />
            </div>

            <div class="col"></div>

            <div class="col-auto pl-4 item-product-action">
                <a href="#" class="btn {{ !$detail->prod_qty ? 'btn-danger' : 'btn-default disabled' }} btn-sm product-delete">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
        </div>
    </div>
</div>
