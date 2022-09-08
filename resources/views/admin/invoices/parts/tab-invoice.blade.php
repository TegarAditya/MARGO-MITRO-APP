<div class="tab-invoice pt-3">
    <input type="hidden" name="nominal" value="{{ $invoice->nominal }}" />

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="required" for="no_suratjalan">{{ trans('cruds.invoice.fields.no_suratjalan') }}</label>
                <input class="form-control {{ $errors->has('no_suratjalan') ? 'is-invalid' : '' }}" type="text" name="no_suratjalan" id="no_suratjalan" value="{{ old('no_suratjalan', $invoice->no_suratjalan) }}" readonly placeholder="(Otomatis)">
                @if($errors->has('no_suratjalan'))
                    <span class="text-danger">{{ $errors->first('no_suratjalan') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.invoice.fields.no_suratjalan_helper') }}</span>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="required" for="no_invoice">{{ trans('cruds.invoice.fields.no_invoice') }}</label>
                <input class="form-control {{ $errors->has('no_invoice') ? 'is-invalid' : '' }}" type="text" name="no_invoice" id="no_invoice" value="{{ old('no_invoice', $invoice->no_invoice) }}" readonly placeholder="(Otomatis)">
                @if($errors->has('no_invoice'))
                    <span class="text-danger">{{ $errors->first('no_invoice') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.invoice.fields.no_invoice_helper') }}</span>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="required" for="order_id">{{ trans('cruds.invoice.fields.order') }}</label>
                <select class="form-control select2 {{ $errors->has('order') ? 'is-invalid' : '' }}" name="order_id" id="order_id" required>
                    @foreach($orders as $id => $entry)
                        <option value="{{ $id }}" {{ (old('order_id') ? old('order_id') : $invoice->order->id ?? '') == $id ? 'selected' : (
                            request('order_id') == $id ? 'selected' : ''
                        ) }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('order'))
                    <span class="text-danger">{{ $errors->first('order') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.invoice.fields.order_helper') }}</span>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="required" for="date">{{ trans('cruds.invoice.fields.date') }}</label>
                <input class="form-control date {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="date" id="date" value="{{ old('date', $invoice->date) }}" required>
                @if($errors->has('date'))
                    <span class="text-danger">{{ $errors->first('date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.invoice.fields.date_helper') }}</span>
            </div>
        </div>
        <div class="col-6">
            @if ($type = !$invoice->id ? -1 : (0 > (int) $invoice->nominal ? 1 : -1))
                <div class="form-group">
                    <label class="required" for="invoice_type">Jenis Invoice</label>
                    <select class="form-control select2 {{ $errors->has('order') ? 'is-invalid' : '' }}" name="invoice_type" id="invoice_type" required>
                        @foreach([
                            -1 => 'Invoice Keluar',
                            1 => 'Invoice Masuk'
                        ] as $id => $entry)
                            <option value="{{ $id }}" {{ (old('invoice_type') ? old('invoice_type') : $type ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('invoice_type'))
                        <span class="text-danger">{{ $errors->first('invoice_type') }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @foreach ([
        [
            'label' => 'Produk Dipilih',
            'items' => $invoice_details,
            'modal' => '#productModal',
            'name' => 'products',
        ],
    ] as $item)
        <hr style="margin: .5em -15px;border-color:#ccc" />

        <div class="product-list-group">
            <h5 class="product-group-title">{{ $item['label'] }}</h5>

            <div class="product-list">
                @if ($item['items']->count())
                    @each('admin.invoices.parts.item-invoice-detail', $item['items'], 'detail')
                @endif

                @include('admin.invoices.parts.item-invoice-detail', [
                    'detail' => new App\Models\OrderDetail,
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
                @include('admin.invoices.parts.item-invoice-detail', [
                    'detail' => new App\Models\OrderDetail,
                    'modal' => $item['modal'],
                    'name' => $item['name'],
                ])

                <div class="product-empty">
                    <p>Belum ada produk yang ditambahkan</p>
                </div>
            </div>
        </div>
    @endforeach

    <div class="product-summary" style="display: {{ !$order->order_details->count() ? 'none' : 'block' }}">
        <div class="row border-top pt-2">
            <div class="col text-right">
                <p class="mb-0">
                    <span class="text-sm">Grand Total</span>
                    <br />
                    <strong class="product-total">@money(data_get($invoice, 'nominal', 0))</strong>
                </p>
            </div>

            @if (!$order->id)
                <div class="col-auto opacity-0 pl-5 order-action-placeholder" style="pointer-events: none">
                    <button type="button" class="btn py-1"></button>
                </div>
            @endif
        </div>
    </div>

    <div class="row mt-4">
        <div class="col"></div>

        <div class="col-auto">
            {{-- {{ !$order->id ? 'btn-primary' : 'btn-secondary' }} --}}
            <button type="submit" class="btn btn-primary">Simpan Invoice</a>
        </div>
    </div>
</div>

@push('footer')
<!-- Modal Products -->
@foreach ([
    [
        'id' => 'productModal',
        'label' => 'Semua Bahan',
        'items' => $order_details,
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

                    <div class="product-select" style="display: {{ !$modal['items']->count() ? 'none' : 'block' }}">
                        @foreach ($modal['items'] as $detail)
                            @php
                            $product = $detail->product;
                            $category = $product->category;
                            $cover = $product->brand;
                            $isi = $product->isi;
                            $jenjang = $product->jenjang;
                            $search = implode(' ', [
                                $product->nama_buku,
                                !$category ? '' : $category->name,
                                !$cover ? '' : $cover->name,
                                !$isi ? '' : $isi->name,
                                !$jenjang ? '' : $jenjang->name,
                            ]);
                            $selected = $invoice_details->where('product_id', $product->id)->count();

                            $order_detail = $order_details->where('product_id', $product->id);
                            $sum_qty = $order_detail->sum('quantity');
                            $sum_moved = $order_detail->sum('moved');
                            $sum_total = $order_detail->sum('total');

                            $bonus = $detail->bonus ?: null;

                            $disabled = (!$order_detail ? false : ($sum_moved >= $sum_qty)) || ($product->stock <= 0);
                            @endphp
                            <a
                                href="{{ route('admin.products.show', $product->id) }}"
                                class="product-select-item{{ $selected ? ' selected' : '' }}{{ $disabled ? ' disabled' : '' }}"
                                data-search="{{ strtolower($search) }}"
                                data-id="{{ $product->id }}"
                                data-price="{{ $product->price }}"
                                data-hpp="{{ $product->hpp }}"
                                data-stock="{{ $product->stock }}"
                                @if($bonus)
                                    data-pg="{{ $bonus->product->id }}"
                                    data-pgqty="{{ $bonus->quantity }}"
                                    data-pgmoved="{{ $bonus->moved }}"
                                    data-pgmax="{{ $bonus->quantity - $bonus->moved }}"
                                @endif
                                @if ($detail)
                                    data-qty="{{ $sum_qty }}"
                                    data-moved="{{ $sum_moved }}"
                                    data-max="{{ $sum_qty - $sum_moved }}"
                                @endif
                                @if ($foto = $product->foto->first())
                                    data-image="{{ $foto->getUrl('thumb') }}"
                                @endif
                                @if ($category = $product->category)
                                    data-category="{{ $category->name }}"
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
                                            <h6 class="text-sm product-name mb-1">{{ $product->nama_buku }}</h6>

                                            <p class="mb-0 text-sm">
                                                Cover - Isi : <span class="product-category">{{ !$cover ? '' : $cover->name }} - {{ !$isi ? '' : $isi->name }}</span>
                                            </p>

                                            <p class="mb-0 text-sm">
                                                Jenjang: <span class="product-category">{{ !$jenjang ? '' : $jenjang->name }}</span>
                                            </p>

                                            <p class="mb-0 text-sm text-bold">
                                                Order Qty: <span class="product-qty-max">{{ $sum_qty ?? '' }}</span>
                                            </p>

                                            <p class="mb-0 text-sm text-bold">
                                                Stock: <span class="product-stock">{{ $product->stock }}</span>
                                            </p>

                                            <p class="mb-0 text-sm text-bold">
                                                Terkirim: <span class="product-moved">{{ $sum_moved ?? '' }}</span>
                                            </p>
                                        </div>
                                        <div style="display: none">
                                            <div class="product-pg">
                                                <h6 class="text-sm product-name mb-1">Product PG/Kunci</h6>

                                                <p class="mb-0 text-sm">
                                                    Order Qty: <span class="product-qty-max">{{ !$bonus ? '' : $bonus->quantity }}</span>
                                                </p>

                                                <p class="mb-0 text-sm">
                                                    Stock: <span class="product-stock">{{ !$bonus ? '' :  $bonus->product->stock }}</span>
                                                </p>

                                                <p class="mb-0 text-sm">
                                                    Terkirim: <span class="product-moved">{{ !$bonus ? '' : $bonus->moved }}</span>
                                                </p>
                                            </div>
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

    .product-select-item.disabled {
        border-color: #727272;
        background-color: #eee;
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
        var form = $('#invoiceForm');

        var orderProduct = form.find('.tab-invoice');
        var people = form.find('#salesperson_id');

        var allProducts = form.find('.product-list');
        var productEmpty = form.find('.product-faker > .product-empty');
        var productSummary = form.find('.product-summary');
        var productTotal = form.find('.product-total');

        var modals = $('.product-modal');
        var productSearch = modals.find('.product-search');
        var productSearchClear = modals.find('.product-search-clear');
        var productSelectItems = modals.find('.product-select-item');
        var productSelectTarget;

        $('.product-list-group').each(function(index, item) {
            var group = $(item);
            var products = group.find('.product-list');
            var productAdd = group.find('.product-add');
            var productFake = group.find('.product-faker > .item-product');

            var bindProduct = function(product) {
                var qty = product.find('.product-qty');
                var actions = product.find('.product-qty-act');
                var actionsPg = product.find('.product-bonus-act');
                var price = product.find('.product-price');
                var priceText = product.find('.product-price_text');
                var bonus = product.find('.product-bonus');

                priceText.val(numeral(price.val()).format('0,0'));

                actions.on('click', function (e) {
                    var el = $(e.currentTarget);
                    var target = product.find(el.data('target'));
                    var qtyNum = parseInt(target.val());
                    var calc = qtyNum + (el.data('action') === '-' ? -1 : 1);
                    var value = calc <= 0 ? 0 : calc;

                    target.filter(':not([readonly])').val(value).trigger('change');
                    calculatePrice();
                });

                actionsPg.on('click', function (e) {
                    var el = $(e.currentTarget);
                    var target = product.find(el.data('target'));
                    var qtyNum = parseInt(target.val());
                    var calc = qtyNum + (el.data('action') === '-' ? -1 : 1);
                    var value = calc <= 0 ? 0 : calc;

                    target.filter(':not([readonly])').val(value).trigger('change');
                });

                qty.on('change blur', function(e) {
                    var el = $(e.currentTarget);
                    var qtyMax = parseInt(Math.min(product.data('max'), product.data('stock')) || 0);
                    var qtyMin = parseInt(product.find('.product-qty').attr('min') || 0);
                    var valueNum = parseInt(el.val());
                    var value = (isNaN(valueNum) || valueNum <= 0) ? qtyMin : valueNum;

                    value = (qtyMax >= 0 && value > qtyMax) ? qtyMax : value;
                    value = (qtyMin && qtyMin > value) ? qtyMin : value;

                    if (value !== valueNum) {
                        el.val(value);
                    }

                    console.log("PRODUCT:M", product, value, qtyMin, qtyMax);
                });

                qty.add(price).on('change keyup blur', function(e) {
                    calculatePrice();
                });

                qty.on('keyup blur', function(e) {
                    let bonus_qty = Math.ceil(qty.val()/33.34);
                    bonus.val(bonus_qty).trigger('change');
                });

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

        var calculatePrice = function() {
            var total = 0;

            allProducts.children().each(function(i, item) {
                var product = $(item);
                var price = parseFloat(product.find('.product-price').val() || 0);
                var qty = product.find('input.product-qty1');
                var qtyNum = parseInt(qty.val() || 0);

                subtotal = (price * qtyNum);
                product.find('.product-subtotal').html(numeral(subtotal).format('$0,0'));

                total += subtotal;
            });

            productTotal.html(numeral(total).format('$0,0'));
            // form.find('#total').val(total);
            form.find('[name="nominal"]').val(total);
        };

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
            var contentBonus = selected.find('.product-pg').clone();
            var qty = product.find('.product-qty').val();
            var price = product.find('.product-price').val();
            var bonus = product.find('.product-bonus').val();
            var name = product.data('name');
            var data = selected.data();

            console.log("ASE", selected, data);

            product.attr('data-id', data.id).data('id', data.id);
            product.attr('data-price', data.price).data('price', data.price);
            product.attr('data-stock', data.stock).data('stock', data.stock);
            product.attr('data-max', data.max).data('max', data.max);
            product.attr('data-qty', data.qty).data('qty', data.qty);
            product.find('.product-col-main').html(content);
            product.find('.product-qty1').val(qty || 0)
                .attr('id', 'fieldQty-'+data.id)
                .attr('name', name+'['+data.id+'][qty]')
                .attr('min', data.max == 0 ? 0 : 1)
                .attr('required', true);
            product.find('.product-price').val(price != 0 ? price : data.price)
                .attr('name', name+'['+data.id+'][price]');
            product.find('.product-price_text').val(numeral(price != 0 ? price : data.price).format('0,0'))
                .attr('id', 'fieldPrice-'+data.id)
                .attr('name', name+'['+data.id+'][price_text]')
                .attr('min', 1)
                .attr('required', true);
            product.find('.product-subtotal').html(numeral(data.price).format('$0,0'));
            product.find('.product-img').attr('src', data.image).parent()[!data.image ? 'hide' : 'show']();

            //bonus
            product.find('.div-product-pg').html(contentBonus);

            if (data.pg) {
                product.find('.div-product-pg').show();
                product.find('.div-product-bonus').show();
                product.find('.product-bonus').val(bonus || 0)
                    .attr('id', 'fieldBonus-'+data.id)
                    .attr('name', name+'['+data.id+'][bonus]')
                    .attr('min', data.pgmax == 0 ? 0 : 1)
                    .attr('max', data.pgmax)
                    .attr('required', true);
            }

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
