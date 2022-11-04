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
$placeholder = !isset($placeholder) ? 'Pilih Produk' : $placeholder;

$po_status = $detail->production_order->status ?? 0;
@endphp
<div class="item-product row item-product-status-{{ $po_status }}" data-id="{{ $product->id }}" data-price="{{ $detail->price ?: $product->price }}" data-hpp="{{ $product->hpp }}" data-name="{{ $name }}">
    <div class="col-5 row">
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

                <x-admin.form-group
                    type="number"
                    id="fieldQty-{{ $product->id }}"
                    :name="!$product->id ? null : $name.'['.$product->id.'][qty]'"
                    containerClass=" m-0"
                    boxClass=" p-0"
                    class="form-control-sm hide-arrows text-center product-qty product-qty1"
                    value="{{ $detail->order_qty ?: 0 }}"
                    min="0"
                    :readonly="0 !== $po_status"
                />
            </div>

            <div class="col" style="max-width: 240px">
                <p class="mb-0 text-sm">Harga</p>

                <input
                    type="hidden"
                    class="product-group"
                    name="{{ !$product->id ? null : $name.'['.$product->id.'][group]' }}"
                    value="{{ $detail->group ?: 0 }}"
                />

                <input
                    type="hidden"
                    class="product-price"
                    name="{{ !$product->id ? null : $name.'['.$product->id.'][price]' }}"
                    value="{{ $detail->ongkos_satuan ?: 0 }}"
                />

                <x-admin.form-group
                    type="text"
                    id="fieldPrice-{{ $product->id }}"
                    :name="!$product->id ? null : $name.'['.$product->id.'][price_text]'"
                    containerClass=" m-0"
                    boxClass=" px-2 py-0"
                    class="form-control-sm product-price_text"
                    value="{{ $detail->ongkos_satuan ?: 0 }}"
                    min="0"
                    :readonly="0 !== $po_status"
                />
            </div>

            <div class="col text-right">
                <p class="text-sm mb-0">Subtotal</p>
                <p class="m-0 product-subtotal">@money($detail->ongkos_total)</p>
            </div>

            <div class="col-auto pl-4 item-product-action">
                @if ($po_status === 0)
                    <a href="#" class="btn {{ !$detail->prod_qty ? 'btn-danger' : 'btn-default disabled' }} btn-sm product-delete">
                        <i class="fa fa-trash"></i>
                    </a>
                @elseif ($po_status === 2)
                    <a href="#" class="btn btn-info btn-sm product-process">
                        <i class="fa fa-check"></i>
                    </a>
                @endif
            </div>
        </div>

        @if ($po_status !== 0)
            <p class="mt-2 pt-1 mb-0 text-sm">
                Proses Produksi
            </p>

            <div class="row">
                @foreach ([
                    [
                        'col' => 'file',
                        'label' => 'File Mentah',
                        'checked' => $detail->file,
                    ],
                    [
                        'col' => 'plate',
                        'label' => 'Cetak Plate',
                        'checked' => $detail->plate,
                    ],
                    [
                        'col' => 'plate_ambil',
                        'label' => 'Ambil Plate',
                        'checked' => $detail->plate_ambil,
                    ],
                ] as $item)
                    <div class="col-4">
                        <i class="fa {{ !$item['checked'] ? 'fa-times' : 'fa-check text-success'}}"></i>

                        <span>
                            {{ $item['label'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
