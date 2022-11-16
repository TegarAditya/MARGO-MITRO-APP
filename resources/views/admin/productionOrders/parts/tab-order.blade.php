@php
$bahan_cat = $categories->where('slug', 'bahan')->first();
$bahan_products = $products->whereIn('category_id', [$bahan_cat->id, ...$bahan_cat->child()->pluck('id')]);

$buku_cat = $categories->where('slug', 'buku')->first();
$buku_products = $products->whereIn('category_id', [$buku_cat->id, ...$buku_cat->child()->pluck('id')]);

$status = $productionOrder->status ?: \App\Models\ProductionOrder::STATUS_PENDING;
@endphp

<div class="model-products pt-3">
    <input type="hidden" name="status" value="{{ $productionOrder->status }}" id="status" />
    <input type="hidden" name="total" value="{{ $productionOrder->total }}" id="total" />

    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="no_order">No. Order Cetak</label>
                <input class="form-control h-auto py-1 {{ $errors->has('no_order') ? 'is-invalid' : '' }}" type="text" name="no_order" id="no_order" value="{{ old('no_order', $productionOrder->no_order) }}" readonly placeholder="(Otomatis)">
                @if($errors->has('no_order'))
                    <span class="text-danger">{{ $errors->first('no_order') }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="required" for="date">Tanggal</label>
                <input class="form-control date h-auto py-1 {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="date" id="date" value="{{ old('date', $productionOrder->date) }}" required>
                @if($errors->has('date'))
                    <span class="text-danger">{{ $errors->first('date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productionOrder.fields.date_helper') }}</span>
            </div>
        </div>

        <div class="col-6">
            <div class="form-group">
                <label class="required" for="type">Jenis</label>
                <select class="form-control select2 {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type" required>
                    <option value="">Please select</option>
                    <option value="cover" {{ old('type', $productionOrder->type) == 'cover' ? 'selected' : '' }}>Cover</option>
                    <option value="isi" {{ old('type', $productionOrder->type) == 'isi' ? 'selected' : '' }}>Isi</option>
                </select>
                @if($errors->has('type'))
                    <span class="text-danger">{{ $errors->first('type') }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="required" for="type_person">Tipe Order</label>
                <select class="form-control select2 {{ $errors->has('type_person') ? 'is-invalid' : '' }}" name="type_person" id="type_person" required>
                    <option value="">Please select</option>
                    <option value="percetakan" selected>Percetakan</option>
                </select>
                @if($errors->has('type_person'))
                    <span class="text-danger">{{ $errors->first('type_person') }}</span>
                @endif
            </div>
        </div>

        <div class="col-6">
            <div class="form-group product-pp{{ !$productionOrder->id ? ' disabled' : ''}}">
                <label class="required" for="productionperson_id">Production Person</label>
                <select class="form-control select2 {{ $errors->has('productionperson') ? 'is-invalid' : '' }}" name="productionperson_id" id="productionperson_id" required>
                    <option value="">Please select</option>

                    @foreach($productionpeople as $person)
                        <option
                            value="{{ $person->id }}"
                            data-type="{{ $person->type }}"
                            {{ old('productionperson_id', $productionOrder->productionperson_id) == $person->id ? 'selected' : '' }}
                        >{{ $person->name }}</option>
                    @endforeach
                </select>

                <span class="product-warn-pp text-info text-sm" style="display: {{ !$productionOrder->type ? 'block' : 'none' }}">
                    Mohon pilih Tipe Order produksi
                </span>

                @if($errors->has('productionperson'))
                    <span class="text-danger">{{ $errors->first('productionperson') }}</span>
                @endif
            </div>
        </div>
    </div>

    <hr style="margin: .5em -15px;border-color:#ccc" />

    <h5 class="mb-2 mt-3">
        {{ !$productionOrder->id ? "Pilih Produk" : "Produk Dipilih" }}
    </h5>

    @if (!$productionOrder->id)
        <div class="product-notice">
            <p>Mohon pilih "Jenis" lebih dulu</p>
        </div>
    @endif

    @if ($status === 0)
        <div class="product-group-action my-3" style="display: {{ !$productionOrder->production_order_details->count() ? 'none' : 'block' }}">
            <div class="row">
                <div class="col">
                    <button type="button" class="btn py-1 border product-group-add" data-add="before">
                        <i class="fa fa-plus text-sm mr-1"></i>

                        <span>Tambah Group</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @foreach ([
        // [
        //     'modal' => '#coverModal',
        //     'name' => 'covers',
        //     'type' => 'cover',
        //     'placeholder' => 'Pilih Cover',
        // ],
        [
            'modal' => '#productModal',
            'name' => 'products',
            'type' => 'isi',
            'placeholder' => 'Pilih Produk',
        ],
    ] as $item)
        @php
        $order_details = $productionOrder->production_order_details;

        $groups = $order_details->groupBy('group');

        if (!$groups->count()) {
            $groups->put('new', collect([]));
        }

        $groups->put('fake', collect([]));
        @endphp
        @foreach ($groups as $group => $items)
            @php
            $parent = $items->first();
            $list = $items->slice(1);
            $all_check = $items->count() === $items->where('file', 1)
                ->where('plate', 1)
                ->where('plate_ambil', 1)
                ->count();

            $label = "Group $loop->iteration"
            @endphp
            <div
                class="product-list-group{{ $group === 'fake' ? ' d-none' : '' }}"
                data-group="{{ $group }}"
                data-max-items="2"
                data-type="{{ $item['type'] }}"
                {{-- style="display: {{ $item['type'] !== $productionOrder->type ? 'none' : 'block' }}" --}}
            >
                {{-- <h6 class="product-group-title font-weight-normal mb-0">{{ $label }}</h6> --}}

                <div class="product-list">
                    @if ($parent)
                        @include('admin.productionOrders.parts.item-product', [
                            'detail' => $parent,
                        ])
                    @endif

                    @if ($list->count())
                        @each('admin.productionOrders.parts.item-product', $list, 'detail')
                    @endif

                    @if ($status === 0)
                        @include('admin.productionOrders.parts.item-product', [
                            'detail' => new App\Models\ProductionOrderDetail,
                            'modal' => $item['modal'],
                            'name' => $item['name'],
                            'placeholder' => $item['placeholder'],
                        ])
                    @endif
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
                    @include('admin.productionOrders.parts.item-product', [
                        'detail' => new App\Models\ProductionOrderDetail,
                        'modal' => $item['modal'],
                        'name' => $item['name'],
                    ])
            
                    <div class="product-empty">
                        <p>Belum ada produk yang ditambahkan</p>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach

    @if ($status === 0)
        <div class="product-group-action my-3" style="display: {{ !$productionOrder->production_order_details->count() ? 'none' : 'block' }}">
            <div class="row">
                <div class="col">
                    <button type="button" class="btn py-1 border product-group-add">
                        <i class="fa fa-plus text-sm mr-1"></i>

                        <span>Tambah Group</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <div class="product-summary" style="display: {{ !$productionOrder->production_order_details->count() ? 'none' : 'block' }}">
        <div class="row border-top pt-2">
            <div class="col text-right">
                <p class="mb-0">
                    <span class="text-sm">Grand Total</span>
                    <br />
                    <strong class="product-total">@money(data_get($productionOrder, 'total', 0))</strong>
                </p>
            </div>

            @if (!$productionOrder->id)
                <div class="col-auto opacity-0 pl-4 ml-1 order-action-placeholder" style="pointer-events: none">
                    <button type="button" class="btn py-1"></button>
                </div>
            @endif
        </div>
    </div>

    <div class="row mt-4">
        <div class="col"></div>

        <div class="col-auto">
            <button type="submit" class="btn {{ !$productionOrder->id ? 'btn-primary' : 'btn-secondary' }}">Simpan Order</a>
        </div>
    </div>
</div>

@push('footer')
<!-- Modal Products -->
@foreach ([
    // [
    //     'id' => 'coverModal',
    //     'type' => 'cover',
    //     'label' => 'Semua Cover',
    //     'items' => $buku_products,
    //     'url' => route("api.products.paginate"),
    //     'selected_ids' => implode(',', []),
    // ],
    [
        'id' => 'productModal',
        'type' => 'isi',
        'label' => 'Semua Produk',
        'items' => $buku_products,
        'url' => route("api.products.paginate"),
        'selected_ids' => implode(',', $productionOrder->production_order_details->pluck('product_id')->toArray()),
    ],
] as $modal)
    <div
        class="modal fade product-modal ajax-product-modal"
        id="{{ $modal['id'] }}"
        tabindex="-1"
        role="dialog"
        data-type="{{ $modal['type'] }}"
    >
        <form action="{{ $modal['url'] }}" id="form{{ $modal['id'] }}">
            <input type="hidden" name="page" value="1" />
            <input type="hidden" name="per_page" value="25" />

            <input type="hidden" name="type" value="" />

            <input type="hidden" name="category_ids" value="{{ implode(',', [$buku_cat->id, ...$buku_cat->child()->pluck('id')]) }}" />
            <input type="hidden" name="selected_ids" value="{{ $modal['selected_ids'] }}" />

            <input type="hidden" name="component" value="components.admin.ajax-product-item" />

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
                            <div class="col">
                                <x-admin.form-group
                                    type="text"
                                    name="search"
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

                            <div class="col-auto pl-0">
                                <button type="submit" class="btn btn-sm btn-primary border-0">
                                    Cari
                                </button>
                            </div>
                        </div>

                        <hr class="mt-0 mb-2" />

                        <div class="product-select" style="display: {{ !$products->count() ? 'none' : 'block' }}"></div>

                        <div class="product-select-loading py-4 text-center" style="display: none">
                            <div class="spinner-border"></div>                        
                        </div>

                        <div class="product-select-empty" style="display: {{ !$modal['items']->count() ? 'block' : 'none' }}">
                            <p class="text-center m-0 py-3">Tidak ada produk</p>
                        </div>

                        <div class="product-select-page-empty" style="display: none">
                            <p class="text-center m-0 py-3">Tidak ada produk di halaman ini</p>
                        </div>
                    </div>

                    <div class="modal-footer row mx-0 px-2 justify-content-start">
                        <div class="col">
                            <div class="product-select-pagination" style="display: none">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item page-prev disabled">
                                            <a class="page-link" href="#">
                                                <i class="fa fa-chevron-left"></i>
                                            </a>
                                        </li>

                                        <li class="page-item page-next">
                                            <a class="page-link" href="#">
                                                <i class="fa fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>

                                <div class="pagination-fake" style="display: none">
                                    <li class="page-item"><a class="page-link" href="#"></a></li>
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endforeach
@endpush

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

.product-pp.disabled > .select2,
.product-pp.disabled > select {
    opacity: 0.5;
    pointer-events: none;
}

.product-list > .item-product:not(:first-child) > .col-5.row {
    padding-left: 5rem;
}

.product-list > .item-product:not(.is-removable):last-child .product-delete {
    opacity: 0.5;
    pointer-events: none;
    background-color: #aeaeae;
    border-color: #969696;
}

.product-list > .item-product.item-product-status-0:last-child > .product-col-content .col {
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

.modal-footer {
    position: sticky;
    position: -webkit-sticky;
    bottom: 0;
    left: 0;
    border-top: 1px solid #ccc;
    background-color: #fff;
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
        var type_person = form.find('#type_person');
        var people = form.find('#productionperson_id');

        var groups = form.find('.product-list-group:not([data-group="fake"])');
        var groupFake = form.find('.product-list-group[data-group="fake"]');
        var groupAction = form.find('.product-group-action');
        var groupAdd = form.find('.product-group-add');
        var allProducts = form.find('.product-list');
        var productEmpty = form.find('.product-faker > .product-empty');
        var productSummary = form.find('.product-summary');
        var productTotal = form.find('.product-total');

        var modals = $('.product-modal');
        var productSelectItems = modals.find('.product-select-item');
        var productSelectTarget;

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
            form.find('#total').val(total);
        };

        var bindGroup = function(item, index) {
            var group = $(item);
            var groupId = group.data('group');
            var maxItems = parseInt(group.data('max-items') || -1);
            var products = group.find('.product-list');
            var productAdd = group.find('.product-add');
            var productFake = group.find('.product-faker > .item-product');

            var bindProduct = function(product) {
                var qty = product.find('.product-qty');
                var actions = product.find('.product-qty-act');
                var price = product.find('.product-price');
                var priceText = product.find('.product-price_text');
                var check = product.find('.product-check');

                !isNaN(groupId) && product.find('.product-group').val(groupId);

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
                    var form = modals.find('form').each(function(index, item) {
                        var selectedInput = $(item).find('[name="selected_ids"]');
                        var selectedIds = (selectedInput.val() || '').split(',').filter(function(item) {
                            return item && item.toString() !== id.toString();
                        });
    
                        selectedInput.val(selectedIds.join(','));
                    });

                    productSelectItems.filter('[data-id="'+id+'"]').removeClass('selected');

                    product.remove();
                    calculatePrice();
                    
                    if (products.children('.item-product').length <= 1) {
                        if (groups.length > 1) {
                            group.remove();

                            groups = groups.filter(':not([data-group="'+group.data('group')+'"])');
                        }
                    }

                    if (groups.find('.product-list > .item-product').length <= 1) {
                        productSummary.hide();
                        groupAction.hide();
                    }

                    maxItems > 0 && products.children('.item-product').show();
                });

                product.find('.product-pick').on('click', function(e) {
                    productSelectTarget = product;
                });

                product.find('.product-check-process').on('click', function(e) {
                    e.preventDefault();

                    var checkBtn = $(e.currentTarget);
                    var isChecked = check.val() == 1;

                    if (isChecked) {
                        products.find('.product-check').val(0);

                        group.removeClass('border-success');
                        checkBtn.removeClass('btn-success')
                            .addClass('btn-light border');
                    } else {
                        products.find('.product-check').val(1);

                        group.addClass('border-success');
                        checkBtn.addClass('btn-success')
                            .removeClass('btn-light border');
                    }
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
        };

        groups.each(function(index, item) {
            bindGroup(item, index);
        });

        groupAdd.on('click', function(e) {
            e.preventDefault();

            var addBtn = $(e.currentTarget);
            var addAfter = (addBtn.data('add') || 'after') === 'after';
            var lastGroup = addAfter ? groups.last() : groups.first();
            var fake = groupFake.clone();

            fake.find('.product-list > .item-product').addClass('is-removable');
            fake.removeClass('d-none')[addAfter ? 'insertAfter' : 'insertBefore'](lastGroup);

            bindGroup(fake, groups.length + 1);
            groups = groups.add(fake);

            groups.each(function(index, item) {
                var group = $(item);
                var groupId = index + 1;

                group.data('group', groupId).attr('data-group', groupId);
                group.find('.product-group').val(groupId);
            });

            allProducts = groups.find('.product-list');
        });

        $('.field-select2').each((index, item) => {
            var el = $(item);
            var placeholder = el.data('placeholder');

            placeholder && el.select2({
                placeholder,
            });
        });

        type.on('change', function(e) {
            var value = e.currentTarget.value;
            var exists = false;

            $('.product-notice')[exists ? 'hide' : 'show']();
        });

        type_person.on('change', function(e) {
            var value = e.currentTarget.value;

            people.val('').trigger('change').select2();
            $('.product-pp')[!value ? 'addClass' : 'removeClass']('disabled');
            $('.product-warn-pp')[!value ? 'show' : 'hide']();

            if (value) {
                people.find('[data-type="'+value+'"]').show().attr('disabled', false);
                people.find(':not([data-type="'+value+'"])').hide().attr('disabled', true);
            }
        });

        if (type_person.val()) {
            type_person.trigger('change');
        }

        function bindProductSelectItem(item) {
            item.on('click', function(e) {
                e.preventDefault();

                var product = productSelectTarget || $('');
                var selected = $(e.currentTarget);
                var content = selected.find('.product-content').clone();
                var qty = product.find('.product-qty').val();
                var price = product.find('.product-price').val();
                var name = product.data('name');
                var data = selected.data();

                var group = product.closest('.product-list-group');
                var maxItems = parseInt(group.data('max-items') || -1);
                var items = group.children('.product-list').children('.item-product');

                var form = selected.closest('form');
                var selectedInput = form.find('[name="selected_ids"]');
                var selectedIds = (selectedInput.val() || '').split(',').filter(function(item) {
                    return !!item;
                });

                if (selectedIds.indexOf(data.id) < 0) {
                    selectedIds.push(data.id);

                    selectedInput.val(selectedIds.join(','));
                }

                product.attr('data-id', data.id).data('id', data.id);
                product.attr('data-price', data.price).data('price', data.price);
                product.attr('data-stock', data.stock).data('stock', data.stock);
                product.find('.product-col-main').html(content);
                product.find('.product-qty1').val(qty || 0)
                    .attr('id', 'fieldQty-'+data.id)
                    .attr('name', name+'['+data.id+'][qty]')
                    .attr('min', 1)
                    .attr('required', true);
                product.find('.product-group')
                    .attr('id', 'fieldGroupProd-'+data.id)
                    .attr('name', name+'['+data.id+'][group]')
                    .attr('required', true);
                product.find('.product-price').val(price != 0 ? price : data.price)
                    .attr('name', name+'['+data.id+'][price]');
                product.find('.product-price_text').val(price != 0 ? price : data.price)
                    .attr('id', 'fieldPrice-'+data.id)
                    .attr('name', name+'['+data.id+'][price_text]')
                    .attr('required', true)
                    .trigger('change');
                product.find('.product-subtotal').html(numeral(data.price).format('$0,0'));
                product.find('.product-img').attr('src', data.image).parent()[!data.image ? 'hide' : 'show']();

                modals.modal('hide');
                selected.addClass('selected');
                productSummary.show();
                groupAction.show();
                calculatePrice();

                product.removeClass('is-removable');
                product.closest('.product-list-group').find('.product-add').trigger('click');

                if (maxItems > 0 && items.length >= maxItems) {
                    items.last().next().hide();
                }
            });
        };

        productSelectItems.each(function(index, item) {
            var el = $(item);

            bindProductSelectItem(el);
        });

        modals.each(function(index, item) {
            var modal = $(item);
            var form = modal.find('form');
            var list = modal.find('.product-select');
            var loading = modal.find('.product-select-loading');
            var empty = modal.find('.product-select-empty');
            var pageEmpty = modal.find('.product-select-page-empty');

            var productSearch = modal.find('.product-search');
            var productSearchClear = modal.find('.product-search-clear');

            var pages = modal.find('.product-select-pagination');
            var pagesUl = pages.find('ul');
            var prev = pages.find('.page-prev');
            var next = pages.find('.page-next');
            var fake = pages.find('.pagination-fake');
            var fakeItem = fake.find('.page-item');

            form.on('submit', function(e) {
                e.preventDefault();

                form.find('[name="page"]').val(1);

                retrieveProducts();
            });

            next.find('a').add(prev.find('a')).on('click', function(e) {
                e.preventDefault();

                var page = $(e.currentTarget).data('page');

                form.find('[name="page"]').val(page);
                retrieveProducts();
            });

            productSearchClear.on('click', function(e) {
                e.preventDefault();

                productSearch.val('').trigger('change');
                retrieveProducts();
            });

            function bindPagination(pagination) {
                prev.removeClass('disabled');
                next.removeClass('disabled');
                pages.show();

                pagesUl.find('.page-item:not(.page-prev):not(.page-next)').remove();

                pagination.links.map(function(item, index) {
                    var label = item.label;

                    console.log("INDEX IS ", index);

                    if (label.toLowerCase().indexOf('prev') >= 0) {
                        var page = pagination.current_page - 1;

                        prev.find('a').attr('data-page', page).data('page', page);

                        pagination.current_page === 1 && prev.addClass('disabled');

                        return void(0);
                    }

                    if (label.toLowerCase().indexOf('next') >= 0) {
                        var page = pagination.current_page + 1;

                        next.find('a').attr('data-page', page).data('page', page);

                        pagination.current_page === pagination.last_page && next.addClass('disabled');

                        return void(0);
                    }

                    var el = fakeItem.clone();

                    if (!item.url) {
                        el.find('a').remove();
                        el.addClass('disabled').html('<span class="page-link">'+item.label+'</span');
                    } else if (item.active) {
                        el.find('a').remove();
                        el.addClass('active').html('<span class="page-link">'+item.label+'</span');
                    } else {
                        el.find('a').attr('href', item.url)
                            .html(item.label)
                            .on('click', function(e) {
                                e.preventDefault();
    
                                form.find('[name="page"]').val(item.label);
                                retrieveProducts();
                            });
                    }

                    el.insertAfter(pagesUl.children().eq(index - 1));
                });
            }

            function retrieveProducts() {
                var action = form.attr('action');

                empty.hide();
                pageEmpty.hide();
                list.hide();
                pages.hide();

                loading.show();

                $.ajax(action, {
                    method: 'GET',
                    data: form.serialize(),
                }).done(function(data) {
                    loading.hide();

                    if (data.pagination?.data?.length) {
                        list.show().html(data.html);
                        pages.show();

                        productSelectItems = modals.find('.product-select-item');

                        productSelectItems.each(function(index, item) {
                            var el = $(item);

                            bindProductSelectItem(el);
                        });

                        bindPagination(data.pagination);
                    } else {
                        pageEmpty.show();
                    }
                }).fail(function(xhr) {
                    loading.hide();

                    empty.show();
                });
            }

            retrieveProducts();
        });
    });
})(jQuery, window.numeral);
</script>
@endpush
