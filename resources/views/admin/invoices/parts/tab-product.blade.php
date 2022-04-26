<div class="order-product pt-3">
    @if (!$invoice->id)
        <div class="product-action mb-4">
            <div class="row align-items-end">
                <div class="col-4">
                    <div class="form-group m-0">
                        <label for="products">Pilih Produk</label>
                        <select class="form-control select2 {{ $errors->has('products') ? 'is-invalid' : '' }} product-options field-select2" name="products" id="products" data-placeholder="Pilih Produk">
                            <option></option>

                            @foreach($order_details as $id => $entry)
                                <option
                                    value="{{ $id }}"
                                    data-id="{{ $entry->product_id }}"
                                    data-price="{{ $entry->price }}"
                                    data-qty="{{ $entry->quantity }}"
                                    data-moved="{{ $entry->moved }}"
                                    data-max="{{ $entry->quantity - $entry->moved }}"
                                    @if ($foto = $entry->product->foto->first())
                                        data-image="{{ $foto->getUrl('thumb') }}"
                                    @endif
                                >{{ $entry->product->name }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('products'))
                            <span class="text-danger">{{ $errors->first('products') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-auto">
                    <button type="button" class="btn py-1 border product-add">Tambah</button>
                </div>
            </div>
        </div>
    @endif

    <h5>Produk Dipilih</h5>

    <div class="product-list">
        @if ($invoice->invoice_details->count())
            @each('admin.invoices.parts.item-invoice-detail', $invoice->invoice_details, 'detail')
        @else
            <div class="product-empty">
                <p>Belum ada produk yang ditambahkan</p>
            </div>
        @endif
    </div>

    <div class="product-summary" style="display: {{ !$invoice->invoice_details->count() ? 'none' : 'block' }}">
        <div class="row border-top pt-2">
            <div class="col text-right">
                <p class="mb-0">
                    <span class="text-sm">Total</span>
                    <br />
                    <strong class="product-total">Rp{{ number_format($invoice->nominal) }}</strong>
                </p>
            </div>

            @if (!$invoice->id)
                <div class="col-auto opacity-0 pl-5 order-action-placeholder" style="pointer-events: none">
                    <button type="button" class="btn py-1"></button>
                </div>
            @endif
        </div>
    </div>

    <div class="product-faker d-none">
        @include('admin.invoices.parts.item-invoice-detail', ['detail' => new App\Models\InvoiceDetail])

        <div class="product-empty">
            <p>Belum ada produk yang ditambahkan</p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col"></div>

        <div class="col-auto">
            @if (!$invoice->id)
                <button type="submit" class="btn btn-primary">Simpan Invoice</a>
            @else
                <a href="#order-2" class="btn btn-dark invoiceTabs-nav">Selanjutnya</a>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
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
</style>
@endpush

@push('scripts')
<script>
(function($, numeral) {
    $(function() {
        var form = $('#invoiceForm');

        var orderProduct = form.find('.order-product');
        var products = form.find('.product-list');
        var productOpts = form.find('.product-options');
        var productAdd = form.find('.product-add');
        var productFake = form.find('.product-faker > .item-product');
        var productEmpty = form.find('.product-faker > .product-empty');
        var productSummary = form.find('.product-summary');
        var productTotal = form.find('.product-total');

        $('.field-select2').each((index, item) => {
            const el = $(item);
            const placeholder = el.data('placeholder');

            placeholder && el.select2({
                placeholder,
            });
        });

        var calculatePrice = function() {
            var total = 0;

            products.children().each(function(i, item) {
                var product = $(item);
                var price = parseFloat(product.find('.product-price').val() || 0);
                var qty = product.find('input.product-qty');
                var qtyNum = parseInt(qty.val() || 0);

                subtotal = (price * qtyNum);
                product.find('.product-subtotal').html(numeral(subtotal).format('$0,0'));

                total += subtotal;
            });

            productTotal.html(numeral(total).format('$0,0'));
        };

        var bindProduct = function(product) {
            var qty = product.find('.product-qty');
            var qtyMax = parseInt(product.data('max') || 0);
            var qtyMin = parseInt(product.find('.product-qty').attr('min') || 0);
            var actions = product.find('.product-qty-act');
            var price = product.find('.product-price');
            var highlightTO;

            actions.on('click', function (e) {
                var el = $(e.currentTarget);
                var qtyNum = parseInt(qty.val());
                var calc = qtyNum + (el.data('action') === '-' ? -1 : 1);
                var value = calc <= 1 ? 1 : calc;

                qty.filter(':not([readonly])').val(value).trigger('change');
                calculatePrice();
            });

            qty.on('change blur', function (e) {
                var el = $(e.currentTarget);
                var valueNum = parseInt(el.val());
                var value = (isNaN(valueNum) || valueNum <= 0) ? qtyMin : (valueNum > qtyMax ? qtyMax : valueNum);

                console.log("ASDASD", value, valueNum, qtyMin, qtyMax);

                value = qtyMin > value ? qtyMin : value;

                if (value !== valueNum) {
                    el.val(value);
                }
            });

            qty.add(price).on('change keyup blur', function(e) {
                calculatePrice();
            });

            product.find('.product-delete').on('click', function(e) {
                product.remove();
                calculatePrice();
                
                if (!products.children('.item-product').length) {
                    productEmpty.clone().appendTo(products);
                    productSummary.hide();
                }
            });

            product.on('highlight', function() {
                highlightTO && clearTimeout(highlightTO);

                product.addClass('highlight');

                highlightTO = setTimeout(() => {
                    product.removeClass('highlight');
                }, 1250);
            });
        };

        products.children('.item-product').each(function(i, item) {
            var product = $(item);

            bindProduct(product);
        });

        productAdd.on('click', function(e) {
            e.preventDefault();

            var selected = productOpts.children(':selected').first();
            var product = productFake.clone();
            var exists = products.children('.item-product[data-id="'+selected.data('id')+'"]');

            if (exists.length) {
                exists.trigger('highlight');

                return void(0);
            }

            if (!selected.data('id')) {
                return void(0);
            }

            var qtyMax = parseInt(selected.data('max') || 0);

            product.attr('data-id', selected.data('id'));
            product.attr('data-price', selected.data('price'));
            product.attr('data-stock', selected.data('stock'));
            product.attr('data-qty', selected.data('qty'));
            product.attr('data-moved', selected.data('moved'));
            product.attr('data-max', qtyMax);
            product.find('.product-name').html(selected.html());
            product.find('.product-category').html(selected.data('category'));
            product.find('.product-stock').html(selected.data('stock'));
            product.find('.product-moved').html(selected.data('moved'));
            product.find('.product-qty-max').html(selected.data('qty'));
            product.find('.product-qty').val(!qtyMax ? 0 : 1)
                .attr('id', 'fieldQty-'+selected.data('id'))
                .attr('name', 'products['+selected.data('id')+'][qty]')
                .attr('max', qtyMax)
                .attr('min', !qtyMax ? 0 : 1)
                .attr('required', true);
            product.find('.product-price').val(selected.data('price'))
                .attr('id', 'fieldPrice-'+selected.data('id'))
                .attr('name', 'products['+selected.data('id')+'][price]')
                .attr('required', true)
            product.find('.product-subtotal').html(numeral(selected.data('price')).format('$0,0'));
            product.find('.product-img').attr('src', selected.data('image')).parent()[!selected.data('image') ? 'hide' : 'show']();

            !products.children('.item-product').length && products.html('');
            product.appendTo(products);

            bindProduct(product);
            productOpts.val('').trigger('change');
            productSummary.show();
            calculatePrice();
        });
    });
})(jQuery, window.numeral);
</script>
@endpush
