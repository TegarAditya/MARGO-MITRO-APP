@php
$status = $productionOrder->status ?? 0;
@endphp

<div class="model-products pt-3">
    <p>
        Beri centang pada sesuai dengan kondisi kesediaan produk
    </p>

    @foreach ([
        [
            'modal' => '#productModal',
            'name' => 'products',
            'placeholder' => 'Pilih Produk',
        ],
    ] as $item)
        @php
        $order_details = $productionOrder->production_order_details;

        $groups = $order_details->groupBy('group');
        @endphp
        @foreach ($groups as $group => $items)
            @php
            $parent = $items->first();
            $list = $items->slice(1);

            $label = "Group $loop->iteration";
            @endphp
            <div
                class="product-list-group"
                data-group="{{ $group }}"
            >
                <div class="product-list">
                    @foreach ($items as $detail)
                        @php
                        $product = $detail->product ?: new App\Models\Product;
                        $category = $product->category;

                        $foto = !$product->foto ? null : $product->foto->first();
                        
                        $po_status = $detail->production_order->status ?? 0;
                        @endphp
                        <div class="item-product row item-product-status-{{ $po_status }}">
                            <div class="col-5 row">
                                <div class="col-auto" style="display: {{ (!$product->id || !$foto) ? 'none' : 'block' }}">
                                    @if ($foto)
                                        <img src="{{ $foto->getUrl('thumb') }}" class="product-img" />
                                    @elseif (!$product->id)
                                        <img src="" class="product-img" />
                                    @endif
                                </div>
                        
                                <div class="col product-col-main {{ !$product->id ? 'align-self-center' : '' }}">
                                    <div class="product-content">
                                        <h6 class="text-sm product-name mb-1">{{ $product->name }}</h6>
                        
                                        <p class="mb-0 text-sm">
                                            Category: <span class="product-category">{{ !$category ? '' : $category->name }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col row justify-content-center align-self-center">
                                <div class="col-6">
                                    <label for="productCheckPlate2-{{ $product->id }}" class="text-sm mb-0">
                                        Plate
                                    </label>

                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="product[{{ $product->id }}][plate_ambil]"
                                            value="1"
                                            id="productCheckPlate2-{{ $product->id }}"
                                            {{ !$detail->plate_ambil ? '' : ' checked' }}
                                        >

                                        <label class="form-check-label" for="productCheckPlate2-{{ $product->id }}">
                                            Sudah ambil plate
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endforeach

    <div class="d-flex justify-content-end mt-4">
        <a href="#model-tab-2" class="btn btn-light modelTabs-nav">
            Sebelumnya
        </a>

        <div class="flex-grow-1"></div>

        <button type="submit" class="btn btn-primary border">
            Simpan
        </button>
    </div>
</div>

@push('styles')
<style>
.product-list-group {
    padding: .5rem 1rem;
    border: 1px solid #eee;
    border-radius: 0.5rem;
    box-shadow: 0 0.25rem 4px rgb(0 0 0 / 12%);
}

.product-list-group + .product-list-group {
    margin-top: 1rem;
}

.product-group-title {
    position: sticky;
    position: -webkit-sticky;
    top: 0;
    z-index: 100;
    background-color: #fff;
    padding: .25em .5em;
    margin-left: -.5em;
    margin-right: -.5em;
}

.product-list > .item-product:not(:first-child) > .col-5.row {
    padding-left: 5rem;
}

.item-product {
    padding: .5rem 0;
    transition: 250ms ease-in-out;
}

.item-product:hover {
    background-color: #f8fbee;
}

.item-product + .item-product {
    border-top: 1px solid #cecece;
}

.item-product.highlight {
    background-color: rgba(32, 201, 151, .25);
}
</style>
@endpush
