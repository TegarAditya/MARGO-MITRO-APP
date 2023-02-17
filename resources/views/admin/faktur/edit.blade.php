@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Faktur
    </div>

    <div class="card-body">
        @if (session()->has('error-message'))
            <p class="text-danger">
                {{session()->get('error-message')}}
            </p>
        @endif
        @php
        $print = function($type) use ($invoice) {
            return route('admin.faktur.show', ['faktur' => $invoice->id, 'print' => $type]);
        };
        @endphp
        <div class="row">
            <div class="col-6 mb-1">
                <span class="badge badge-warning">Surat Jalan</span>
            </div>

            <div class="col-6 text-right">
                <a href="{{ route('admin.invoices.edit', $invoice->id) }}" class="border-bottom">
                    <i class="fa fa-edit"></i> Edit
                </a>
            </div>

            <div class="col-3">
                <p class="mb-0 text-sm">
                    No. Invoice
                    <br />
                    <strong>{{ $invoice->no_invoice }}</strong>
                </p>
            </div>

            <div class="col-3">
                <p class="mb-0 text-sm">
                    No. Surat Jalan
                    <br />
                    <strong>{{ $invoice->no_suratjalan }}</strong>

                    <a href="{{ $print('sj') }}" class="fa fa-lg fa-print ml-1 text-info" title="Print Surat Jalan" target="_blank"></a>
                </p>
            </div>

            <div class="col-3">
                <p class="mb-0 text-sm">
                    Semester
                    <br />
                    <strong>{{ $invoice->order->semester->name }}</strong>
                </p>
            </div>

            <div class="col text-right">
                <span>Tanggal<br />{{ $invoice->date }}</span>
            </div>
        </div>

        <div class="row">
            <div class="col-3">
                <p class="mb-0 text-sm">
                    Salesman
                    <br />
                    <strong>{{ $invoice->order->salesperson->name }}</strong>
                </p>
            </div>

            <div class="col-3">
                <p class="mb-0 text-sm">
                    Alamat
                    <br />
                    <strong>{{ $invoice->alamat ?? '-' }}</strong>
                </p>
            </div>
        </div>
        <br>
        <hr style="margin: 0.5em -15px 30px -15px;border-color:#ccc" />
        <form method="POST" action="{{ route("admin.faktur.update", [$invoice->id]) }}" enctype="multipart/form-data" id="invoiceForm">
            @method('PUT')
            @csrf
            <input type="hidden" name="nominal" value="{{ $invoice->nominal }}" />
            @foreach ([
            [
                'label' => 'Produk Yang Akan Dikirim',
                'items' => $invoice_details,
                'modal' => '#productModal',
                'name' => 'products',
            ],
            ] as $item)
                <div class="product-list-group">
                    <div class="row mb-2">
                        <div class="col py-1">
                            <h5 class="product-group-title">{{ $item['label'] }}</h5>
                        </div>
                        <div class="col-4 py-1">
                            <x-admin.form-group
                                type="text"
                                name="element-product-search"
                                id="element-product-search"
                                containerClass=" m-0"
                                boxClass=" p-0"
                                class="form-control-sm element-product-search px-1"
                            >
                                <x-slot name="left">
                                    <button type="button" class="btn btn-sm border-0 px-2 element-product-search-act">
                                        <i class="fa fa-search text-sm"></i>
                                    </button>
                                </x-slot>

                                <x-slot name="right">
                                    <button type="button" class="btn btn-sm border-0 px-2 element-product-search-clear" id="element-product-search-clear">
                                        <i class="fa fa-times text-sm"></i>
                                    </button>
                                </x-slot>
                            </x-admin.form-group>
                        </div>
                        <div class="col-2">
                            <button type="submit" class="btn btn-md btn-primary float-right">Simpan Faktur</a>
                        </div>
                    </div>

                    <div class="product-list">
                        {{-- @include('admin.faktur.parts.item-invoice-detail', [
                            'detail' => new App\Models\OrderDetail,
                            'modal' => $item['modal'],
                            'name' => $item['name'],
                        ]) --}}
                        <hr style="margin: .5em -15px;border-color:#ccc" />

                        @if ($item['items']->count())
                            @php
                                $sortedProducts = $item['items']->sortByDesc('product.tipe_pg')->sortBy('product.halaman_id')->sortBy('product.kelas_id')->sortBy('product.name')->sortBy('product.jenjang_id');
                            @endphp
                            @each('admin.faktur.parts.item-invoice-detail', $sortedProducts, 'detail')
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
                        @include('admin.faktur.parts.item-invoice-detail', [
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
            <div class="row mt-4">
                <div class="col"></div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Simpan Faktur</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

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

    .product-list > .item-product:first-child .product-delete {
        opacity: 0.5;
        pointer-events: none;
        background-color: #aeaeae;
        border-color: #969696;
    }

    .product-list > .item-product:first-child > .product-col-content {
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
        var elementProductSearch = $('#element-product-search');
        var elementProductSearchClear = $('#element-product-search-clear');

        var jenjang = ['3', '4', '11'];

        elementProductSearch.on('change keyup blur', function(e) {
            var keyword = $(e.currentTarget).val().toLowerCase();

            allProducts.children().each(function(i, item) {
                var el = $(item);
                var search = String(el.find('.product-name').html()).toLowerCase();
                var results = 0;

                el.show();

                keyword.split(';').map(function(key) {
                    search.includes(key) ? (results++) : el.hide();
                });

                if (!results && i !== 0) {
                    el.hide();
                } else {
                    el.show();
                }
            });
        });

        elementProductSearchClear.on('click', function(e) {
            e.preventDefault();
            elementProductSearch.val('').trigger('change');
        });

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
                    // var qtyMax = parseInt(Math.min(product.data('max'), product.data('stock')) || 0);
                    var qtyMax = parseInt(product.data('max') || 0);
                    var qtyMin = parseInt(product.find('.product-qty').attr('min') || 0);
                    var valueNum = parseInt(el.val());
                    var value = (isNaN(valueNum) || valueNum <= 0) ? qtyMin : valueNum;

                    value = (qtyMax >= 0 && value > qtyMax) ? qtyMax : value;
                    value = (qtyMin && qtyMin > value) ? qtyMin : value;

                    if (value !== valueNum) {
                        el.val(value);
                    }
                });

                qty.add(price).on('change keyup blur', function(e) {
                    calculatePrice();
                });

                // qty.on('keyup blur', function(e) {
                //     let jenjang_id = product.attr('data-jenjang');
                //     let pembagi = 100;
                //     if(jenjang.includes(jenjang_id)){
                //         pembagi = 33.34;
                //     }
                //     console.log(pembagi);
                //     let bonus_qty = Math.ceil(qty.val()/pembagi);
                //     bonus.val(bonus_qty).trigger('change');
                // });

                bonus.on('change keyup blur', function(e) {
                    var el = $(e.currentTarget);
                    // var qtyMax = parseInt(Math.min(product.data('pgmax'), product.data('pgstock')) || 0);
                    var qtyMax = parseInt(Math.min(product.data('pgmax')) || 0);
                    var qtyMin = parseInt(0);
                    var valueNum = parseInt(el.val());
                    var value = (isNaN(valueNum) || valueNum <= 0) ? qtyMin : valueNum;

                    value = (qtyMax >= 0 && value > qtyMax) ? qtyMax : value;
                    value = (qtyMin && qtyMin > value) ? qtyMin : value;

                    if (value !== valueNum) {
                        el.val(value);
                    }
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
                product.prependTo(products);

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

                    keyword.split(';').map(function(key) {
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

            // console.log("ASE", selected, data);

            product.attr('data-id', data.id).data('id', data.id);
            product.attr('data-price', data.price).data('price', data.price);
            product.attr('data-stock', data.stock).data('stock', data.stock);
            product.attr('data-max', data.max).data('max', data.max);
            product.attr('data-qty', data.qty).data('qty', data.qty);
            product.attr('data-jenjang', data.jenjang).data('stock', data.jenjang);
            product.find('.product-col-main').html(content);
            product.find('.product-qty1').val(qty || 0)
                .attr('id', 'fieldQty-'+data.id)
                .attr('name', name+'['+data.id+'][qty]')
                .attr('min', 0)
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
                product.attr('data-pg', data.pg).data('pg', data.pg);
                product.attr('data-pgstock', data.pgstock).data('pgstock', data.pgstock);
                product.attr('data-pgqty', data.pgqty).data('pgqty', data.pgqty);
                product.attr('data-pgmoved', data.pgmoved).data('pgqty', data.pgmoved);
                product.attr('data-pgmax', data.pgmax).data('pgmax', data.pgmax);
                product.find('.div-product-pg').show();
                product.find('.div-product-bonus').show();
                product.find('.product-bonus').val(bonus || 0)
                    .attr('id', 'fieldBonus-'+data.id)
                    .attr('name', name+'['+data.id+'][bonus]')
                    .attr('min', 0)
                    .attr('max', data.pgmax)
                    .attr('readonly', data.pgmax == 0 ? true : false)
                    .attr('required', true);
            }

            // productSearchClear.trigger('click');
            modals.modal('hide');
            selected.addClass('selected');
            productSummary.show();
            calculatePrice();

            product.closest('.product-list-group').find('.product-add').trigger('click');
        });

        $('.detail-invoice-delete').on('click', function(e) {
            e.preventDefault();

            var el = $(e.currentTarget);
            var url = el.attr('href');
            var invoice = el.data('invoice');
            var order = el.data('order');

            if (confirm('{{ trans('global.areYouSure') }}')) {
                $.ajax({
                    headers: {'x-csrf-token': _token},
                    method: 'POST',
                    url: url,
                    data: {
                        invoice, order
                    }
                }).done(function () {
                    location.reload();
                });
            }
        });
    });
})(jQuery, window.numeral);
</script>
@endpush
