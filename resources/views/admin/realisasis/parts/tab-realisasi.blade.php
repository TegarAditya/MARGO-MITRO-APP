@php
$bahan_cat = $categories->where('slug', 'bahan')->first();
$bahan_products = $products->whereIn('category_id', [$bahan_cat->id, ...$bahan_cat->child()->pluck('id')]);

$buku_cat = $categories->where('slug', 'buku')->first();
$buku_products = $products->whereIn('category_id', [$buku_cat->id, ...$buku_cat->child()->pluck('id')]);
@endphp

<div class="model-products pt-3">
    <input type="hidden" name="nominal" value="{{ $realisasi->nominal }}" id="total" />

    <div class="form-group">
        <label class="required" for="no_realisasi">No. Realisasi</label>
        <input class="form-control {{ $errors->has('no_realisasi') ? 'is-invalid' : '' }}" type="text" name="no_realisasi" id="no_realisasi" value="{{ old('no_realisasi', $realisasi->no_realisasi) }}" readonly placeholder="(Otomatis)">
        @if($errors->has('no_realisasi'))
            <span class="text-danger">{{ $errors->first('no_realisasi') }}</span>
        @endif
    </div>
    <div class="form-group">
        <label class="required" for="finishing_order_id">Finishing Order</label>
        <select class="form-control select2 {{ $errors->has('order') ? 'is-invalid' : '' }}" name="finishing_order_id" id="finishing_order_id" required>
            @foreach($finishingOrders as $id => $entry)
                <option value="{{ $id }}" {{ (old('finishing_order_id') ? old('finishing_order_id') : $realisasi->finishing_order->id ?? '') == $id ? 'selected' : (
                    request('finishing_order_id') == $id ? 'selected' : ''
                ) }}>{{ $entry }}</option>
            @endforeach
        </select>
        @if($errors->has('finishing_order_id'))
            <span class="text-danger">{{ $errors->first('finishing_order_id') }}</span>
        @endif
    </div>
    <div class="form-group">
        <label class="required" for="date">Tanggal</label>
        <input class="form-control date {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="date" id="date" value="{{ old('date', $realisasi->date) }}" required>
        @if($errors->has('date'))
            <span class="text-danger">{{ $errors->first('date') }}</span>
        @endif
    </div>

    @foreach ([
        [
            'label' => 'Produk Dipilih',
            'product_ids' => $buku_products->pluck('id'),
            'modal' => '#productModal',
            'name' => 'products',
        ], [
            'label' => 'Bahan Dipilih',
            'product_ids' => $bahan_products->pluck('id'),
            'modal' => '#bahanModal',
            'name' => 'products',
        ],
    ] as $item)
        @php
        $details = $fo_details->whereIn('product_id', $item['product_ids']);
        @endphp
        <hr style="margin: .5em -15px;border-color:#ccc" />

        <div class="product-list-group">
            <h5 class="product-group-title">{{ $item['label'] }}</h5>

            <div class="product-list">
                @if ($realisasi->id && $details->count())
                    @each('admin.realisasis.parts.item-realisasi-detail', $details, 'detail')
                @endif

                @include('admin.realisasis.parts.item-realisasi-detail', [
                    'detail' => new App\Models\FinishingOrderDetail,
                    'modal' => $item['modal'],
                    'name' => $item['name'],
                ])
            </div>

            <div class="product-action mb-1 mt-2 py-2 border-top{{ $errors->has($item['name']) ? '' : ' d-none'}}">
                <div class="row justify-content-center d-none">
                    <div class="col-auto">
                        <button type="button" class="btn py-1 border product-add">
                            <i class="fa fa-plus text-sm mr-1"></i>

                            <span>Tambah Produk</span>
                        </button>
                    </div>
                </div>

                @if($errors->has($item['name']))
                    <span class="text-danger">{{ $errors->first($item['name']) }}</span>
                @endif
            </div>

            <div class="product-faker d-none">
                @include('admin.realisasis.parts.item-realisasi-detail', [
                    'detail' => new App\Models\FinishingOrderDetail,
                    'modal' => $item['modal'],
                    'name' => $item['name'],
                ])

                <div class="product-empty">
                    <p>Belum ada produk yang ditambahkan</p>
                </div>
            </div>
        </div>
    @endforeach

    <div class="product-summary" style="display: {{ !$finishingOrder->finishing_order_details->count() ? 'none' : 'block' }}">
        <div class="row border-top pt-2">
            <div class="col text-right">
                <p class="mb-0">
                    <span class="text-sm">Grand Total</span>
                    <br />
                    <strong class="product-total">@money(data_get($finishingOrder, 'total', 0))</strong>
                </p>
            </div>

            @if (!$finishingOrder->id)
                <div class="col-auto opacity-0 pl-5 order-action-placeholder" style="pointer-events: none">
                    <button type="button" class="btn py-1"></button>
                </div>
            @endif
        </div>
    </div>

    <div class="row mt-4">
        <div class="col"></div>

        <div class="col-auto">
            {{-- {{ !$finishingOrder->id ? 'btn-primary' : 'btn-secondary' }} --}}
            <button type="submit" class="btn btn-primary">Simpan Realisasi</a>
        </div>
    </div>
</div>

@push('footer')
<!-- Modal Products -->
@foreach ([
    [
        'id' => 'bahanModal',
        'label' => 'Semua Bahan',
        'items' => $bahan_products->filter(function($item) use ($fo_details, $finishingOrder) {
            return !$finishingOrder->id ? true : $fo_details->where('product_id', $item->id)->count();
        }),
    ], [
        'id' => 'productModal',
        'label' => 'Semua Produk',
        'items' => $buku_products->filter(function($item) use ($fo_details, $finishingOrder) {
            return !$finishingOrder->id ? true : $fo_details->where('product_id', $item->id)->count();
        }),
    ],
] as $modal)
    <div class="modal fade product-modal" id="{{ $modal['id'] }}" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-body">
                    <div class="row position-sticky top-0 py-2 bg-white" style="z-index: 10">
                        <div class="col">
                            <h4 class="mb-0">Semua Produk</h4>
                        </div>

                        <div class="col-auto align-self-center">
                            <button type="button" class="btn btn-sm btn-default px-2" data-toggle="modal" data-target="#{{ $modal['id'] }}">
                                <span class="text-xs">Tutup</span>
                            </button>
                        </div>
                    </div>

                    <p class="mb-0">Pilih produk yang akan ditambahkan:</p>

                    <div class="row align-items-center product-searchbar py-2">
                        <div class="col-12">
                            <x-admin.form-group
                                type="text"
                                name="product-search"
                                containerClass=" m-0"
                                boxClass=" p-0"
                                class="form-control-sm product-search px-1"
                            >
                                <x-slot name="left">
                                    <button type="button" class="btn btn-sm border-0 px-2 product-search-act">
                                        <i class="fa fa-search text-sm"></i>
                                    </button>
                                </x-slot>

                                <x-slot name="right">
                                    <button type="button" class="btn btn-sm border-0 px-2 product-search-clear">
                                        <i class="fa fa-times text-sm"></i>
                                    </button>
                                </x-slot>
                            </x-admin.form-group>
                        </div>
                    </div>

                    <hr class="mt-0 mb-2" />

                    <div class="product-select" style="display: {{ !$fo_details->count() ? 'none' : 'block' }}">
                        @foreach ($modal['items'] as $product)
                            @php
                            $category = $product->category;
                            $fo_detail = $fo_details->where('product_id', $product->id)->first();
                            $search = implode(' ', [
                                $product->name,
                                !$category ? '' : $category->name,
                            ]);
                            $selected = false;
                            @endphp
                            <a
                                href="{{ route('admin.products.show', $product->id) }}"
                                class="product-select-item{{ $selected ? ' selected' : '' }}"
                                data-search="{{ strtolower($search) }}"
                                data-id="{{ $product->id }}"
                                data-price="{{ $product->price }}"
                                data-hpp="{{ $product->hpp }}"
                                data-stock="{{ $product->stock }}"
                                @if ($fo_detail)
                                    data-qty="{{ $fo_detail->order_qty }}"
                                    data-prod="0"
                                @endif
                                @if ($foto = $product->foto->first())
                                    data-image="{{ $foto->getUrl('thumb') }}"
                                @endif
                            >
                                <div class="row">
                                    @if ($product->foto && $foto = $product->foto->first())
                                        <div class="col-auto pr-1">
                                            <img src="{{ $foto->getUrl('thumb') }}" class="product-img" />
                                        </div>
                                    @endif

                                    <div class="col">
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
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="product-select-empty" style="display: {{ !$modal['items']->count() ? 'block' : 'none' }}">
                        <p class="text-center m-0 py-3">Tidak ada produk</p>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endpush

@push('styles')
<style>
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

.product-pp.disabled > .select2,
.product-pp.disabled > select {
    opacity: 0.5;
    pointer-events: none;
}

.product-list > .item-product:last-child .product-delete {
    opacity: 0.5;
    pointer-events: none;
    background-color: #aeaeae;
    border-color: #969696;
}

.product-list > .item-product:last-child > .product-col-content {
    opacity: 0.66;
    pointer-events: none;
}

.product-action {
    position: sticky;
    position: -webkit-sticky;
    z-index: 10;
    bottom: 0;
    background-color: #fff;
    margin: 0 -1rem;
    padding: 0 1rem;
}

.item-product {
    padding: .5rem 0;
    transition: 250ms ease-in-out;
}

.item-product + .item-product {
    border-top: 1px solid #cecece;
}

.item-product.highlight {
    background-color: rgba(32, 201, 151, .25);
}

.product-searchbar {
    position: sticky;
    position: -webkit-sticky;
    top: 0;
    z-index: 100;
    background-color: #fff;
    padding: .5rem 0;
}

.product-select-item {
    display: block;
    border: 1px solid #eee;
    border-radius: .25rem;
    padding: .5rem .5rem;
    background-color: #fff;
    color: #323232
}

.product-select-item:not(.selected):hover {
    border: 1px solid #cfcfcf;
    background-color: #fafafa;
    color: var(--blue);
}

.product-select-item.selected {
    border-color: var(--success);
    background-color: #eafdef;
    pointer-events: none;
}

.product-select-item + .product-select-item {
    margin-top: .5rem;
}

.select2-container--default .select2-results__option[aria-disabled=true] {
    display: none;
}
</style>
@endpush

@push('scripts')
<script>
(function($, numeral) {
    $(function() {
        var form = $('#modelForm');

        var orderProduct = form.find('.model-products');
        var type = form.find('#type');
        var people = form.find('#productionperson_id');

        var allProducts = form.find('.product-list');
        var productEmpty = form.find('.product-faker > .product-empty');
        var productSummary = form.find('.product-summary');
        var productTotal = form.find('.product-total');

        var modals = $('.product-modal');
        var productSearch = modals.find('.product-search');
        var productSearchClear = modals.find('.product-search-clear');
        var productSelectItems = modals.find('.product-select-item');
        var productSelectTarget;

        var calculatePrice = function() {
            var total = 0;

            allProducts.children().each(function(i, item) {
                var product = $(item);
                var price = parseFloat(product.find('.product-price').val() || 0);
                var qty = product.find('input.product-qty2');
                var qtyNum = parseInt(qty.val() || 0);

                subtotal = (price * qtyNum);
                product.find('.product-subtotal').html(numeral(subtotal).format('$0,0'));

                total += subtotal;
            });

            productTotal.html(numeral(total).format('$0,0'));
            form.find('#total').val(total);
        };

        $('.product-list-group').each(function(index, item) {
            var group = $(item);
            var products = group.find('.product-list');
            var productAdd = group.find('.product-add');
            var productFake = group.find('.product-faker > .item-product');

            var bindProduct = function(product) {
                var qty = product.find('.product-qty');
                var actions = product.find('.product-qty-act');
                var price = product.find('.product-price');
                var priceText = product.find('.product-price_text');

                actions.on('click', function (e) {
                    var el = $(e.currentTarget);
                    var target = product.find(el.data('target'));
                    var qtyNum = parseInt(target.val());
                    var calc = qtyNum + (el.data('action') === '-' ? -1 : 1);
                    var value = calc <= 0 ? 0 : calc;

                    target.filter(':not([readonly])').val(value).trigger('change');
                    calculatePrice();
                });

                qty.add(price).on('change keyup blur', function(e) {
                    calculatePrice();
                });

                priceText.on('change keyup blur', function(e) {
                    var value = numeral(e.target.value);

                    priceText.val(value.format('$0,0'));
                    price.val(value.value()).trigger('change');
                }).trigger('change');

                product.find('.product-delete').on('click', function(e) {
                    e.preventDefault();

                    var id = product.attr('data-id');

                    productSelectItems.filter('[data-id="'+id+'"]').removeClass('selected');

                    product.remove();
                    calculatePrice();

                    if (!products.children('.item-product').length) {
                        productEmpty.clone().appendTo(products);
                        productSummary.hide();
                    }
                });

                product.find('.product-pick').on('click', function(e) {
                    productSelectTarget = product;
                });
            };

            products.children('.item-product').each(function(i, item) {
                var product = $(item);

                bindProduct(product);
            });

            productAdd.on('click', function(e) {
                e.preventDefault();

                var product = productFake.clone();

                !products.children('.item-product').length && products.html('');
                product.appendTo(products);

                bindProduct(product);
                group.find('.product-action').hide();
            });
        });

        $('.field-select2').each((index, item) => {
            const el = $(item);
            const placeholder = el.data('placeholder');

            placeholder && el.select2({
                placeholder,
            });
        });

        type.on('change', function(e) {
            var value = e.currentTarget.value;

            people.val('').trigger('change').select2();
            $('.product-pp')[!value ? 'addClass' : 'removeClass']('disabled');
            $('.product-warn-pp')[!value ? 'show' : 'hide']();

            if (value) {
                people.find('[data-type="'+value+'"]').show().attr('disabled', false);
                people.find(':not([data-type="'+value+'"])').hide().attr('disabled', true);
            }
        });

        modals.each(function(index, item) {
            var modal = $(item);
            var productSearch = modal.find('.product-search');
            var productSearchClear = modal.find('.product-search-clear');
            var productSelect = modal.find('.product-select');
            var productSelectEmpty = modal.find('.product-select-empty');
            var items = modal.find('.product-select-item');

            productSearch.on('change keyup blur', function(e) {
                var keyword = $(e.currentTarget).val().toLowerCase();
                var results = !keyword ? 1 : 0;

                items.show();

                keyword && items.each(function(i, item) {
                    var el = $(item);
                    var search = el.data('search');

                    keyword.split(' ').map(function(key) {
                        search.indexOf(key) < 0 ? el.hide() : (results++);
                    });
                });

                productSelect[!results ? 'hide' : 'show']();
                productSelectEmpty[!results ? 'show' : 'hide']();
            });

            productSearchClear.on('click', function(e) {
                e.preventDefault();
                productSearch.val('').trigger('change');
            });
        });

        productSelectItems.on('click', function(e) {
            e.preventDefault();

            var product = productSelectTarget || $('');
            var selected = $(e.currentTarget);
            var content = selected.find('.product-content').clone();
            var name = product.data('name');
            var data = selected.data();

            product.attr('data-id', data.id).data('id', data.id);
            product.attr('data-price', data.price).data('price', data.price);
            product.attr('data-stock', data.stock).data('stock', data.stock);
            product.find('.product-col-main').html(content);
            product.find('.product-qty1').val(data.qty || 0)
                .attr('id', 'fieldQty-'+data.id)
                .attr('name', name+'['+data.id+'][qty]')
                .attr('required', true);
            product.find('.product-qty2').val(data.prod || 0)
                .attr('id', 'fieldQtyProd-'+data.id)
                .attr('name', name+'['+data.id+'][prod]')
                .attr('min', 1)
                .attr('required', true);
            product.find('.product-price').val(data.price || 0)
                .attr('name', name+'['+data.id+'][price]');
            product.find('.product-price_text').val(data.price || 0)
                .attr('id', 'fieldPrice-'+data.id)
                .attr('name', name+'['+data.id+'][price_text]')
                .attr('required', true)
                .trigger('change');
            product.find('.product-subtotal').html(numeral(data.price).format('$0,0'));
            product.find('.product-img').attr('src', data.image).parent()[!data.image ? 'hide' : 'show']();

            productSearchClear.trigger('click');
            modals.modal('hide');
            selected.addClass('selected');
            productSummary.show();
            calculatePrice();

            product.closest('.product-list-group').find('.product-add').trigger('click');
        });
    });
})(jQuery, window.numeral);
</script>
@endpush
