<div class="order-product pt-3">
    <form method="POST" action="{{ !$order->id ? route('admin.orders.store') : route("admin.orders.update", [$order->id]) }}" enctype="multipart/form-data" id="orderForm">
        @method(!$order->id ? 'POST' : 'PUT')
        @csrf
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="no_order">No. Order</label>
                    <input class="form-control h-auto py-1 {{ $errors->has('no_order') ? 'is-invalid' : '' }}" type="text" name="no_order" id="no_order" value="{{ old('no_order', $order->no_order) }}" readonly placeholder="(Otomatis)">
                    @if($errors->has('no_order'))
                        <span class="text-danger">{{ $errors->first('no_order') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.order.fields.date_helper') }}</span>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="required" for="date">{{ trans('cruds.order.fields.date') }}</label>
                    <input class="form-control date h-auto py-1 {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="date" id="date" value="{{ old('date', $order->date) }}" required>
                    @if($errors->has('date'))
                        <span class="text-danger">{{ $errors->first('date') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.order.fields.date_helper') }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label class="required" for="salesperson_id">{{ trans('cruds.order.fields.salesperson') }}</label>
                    <select class="form-control select2 {{ $errors->has('salesperson') ? 'is-invalid' : '' }}" name="salesperson_id" id="salesperson_id">
                        @foreach($salespeople as $id => $entry)
                            <option value="{{ $id }}" {{ (old('salesperson_id') ? old('salesperson_id') : $order->salesperson->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('salesperson'))
                        <span class="text-danger">{{ $errors->first('salesperson') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.order.fields.salesperson_helper') }}</span>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="required" for="semester_id">{{ trans('cruds.order.fields.semester') }}</label>
                    <select class="form-control select2 {{ $errors->has('semester_id') ? 'is-invalid' : '' }}" name="semester_id" id="semester_id">
                        @foreach($semesters as $id => $entry)
                            <option value="{{ $id }}" {{ (old('semester_id') ? old('semester_id') : $order->semester->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('semester_id'))
                        <span class="text-danger">{{ $errors->first('semester_id') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.order.fields.semester_helper') }}</span>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="required" for="kota_sales_id">Kota Sales</label>
                    <select class="form-control select2 {{ $errors->has('kota_sales') ? 'is-invalid' : '' }}" name="kota_sales_id" id="kota_sales_id">
                        @foreach($kotasales as $id => $entry)
                            <option value="{{ $id }}" {{ (old('kota_sales_id') ? old('kota_sales_id') : $order->kota_sales_id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('kota_sales'))
                        <span class="text-danger">{{ $errors->first('kota_sales') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.order.fields.salesperson_helper') }}</span>
                </div>
            </div>
        </div>
        <hr style="margin: .5em -15px;border-color:#ccc" />
        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    <label for="custom_price">Custom Price</label>
                    <select class="form-control select2 {{ $errors->has('custom_price') ? 'is-invalid' : '' }}" name="custom_price" id="custom_price">
                        @foreach($customprices as $id => $entry)
                            <option value="{{ $id }}" {{ request('custom_price') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('custom_price'))
                        <span class="text-danger">{{ $errors->first('custom_price') }}</span>
                    @endif
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label class="required" for="cover">Cover</label>
                    <select class="form-control select2 {{ $errors->has('cover') ? 'is-invalid' : '' }}" name="cover" id="cover">
                        @foreach($covers as $id => $entry)
                            <option value="{{ $id }}" {{ request('cover') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('cover'))
                        <span class="text-danger">{{ $errors->first('cover') }}</span>
                    @endif
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label class="required" for="isi">Isi</label>
                    <select class="form-control select2 {{ $errors->has('isi') ? 'is-invalid' : '' }}" name="isi" id="isi">
                        @foreach($isi as $id => $entry)
                            <option value="{{ $id }}" {{ request('isi') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('isi'))
                        <span class="text-danger">{{ $errors->first('isi') }}</span>
                    @endif
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label class="required" for="jenjang">{{ trans('cruds.buku.fields.jenjang') }} {{ old('jenjang') }}</label>
                    <select class="form-control select2 {{ $errors->has('jenjang') ? 'is-invalid' : '' }}" name="jenjang" id="jenjang">
                        @foreach($jenjang as $id => $entry)
                            <option value="{{ $id }}" {{ request('jenjang') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('jenjang'))
                        <span class="text-danger">{{ $errors->first('jenjang') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.buku.fields.jenjang_helper') }}</span>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label class="required" for="kelas_id">{{ trans('cruds.buku.fields.kelas') }}</label>
                    <select class="form-control select2 {{ $errors->has('kelas') ? 'is-invalid' : '' }}" name="kelas" id="kelas">
                        @foreach($kelas as $id => $entry)
                            <option value="{{ $id }}" {{ request('kelas') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('kelas'))
                        <span class="text-danger">{{ $errors->first('kelas') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.buku.fields.kelas_helper') }}</span>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label class="required" for="semester">{{ trans('cruds.buku.fields.semester') }}</label>
                    <select class="form-control select2 {{ $errors->has('semester') ? 'is-invalid' : '' }}" name="semester" id="semester">
                        @foreach($semesters as $id => $entry)
                            <option value="{{ $id }}" {{ request('semester') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('semester'))
                        <span class="text-danger">{{ $errors->first('semester') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.buku.fields.semester_helper') }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary mr-3" name="filter" value="filter">Filter Buku</button>
                    <a class="btn btn-warning mr-3" data-toggle="modal" data-target="#gantiHargaModal">Ganti Harga</a>
                    @if($order->id)
                        <a id="synchronFaktur" href="{{ route('admin.orders.ubahHargaFaktur') }}" data-order="{{ $order->id }}" class="btn btn-danger mr-3" name="synchron" value="synchron">Synchron Harga Faktur</a>
                    @endif
                </div>
            </div>
        </div>

        @foreach ([
            [
                'label' => 'Produk Dipilih',
                'items' => $order->order_details,
                'modal' => '#productModal',
                'name' => 'products',
            ],
        ] as $item)
            <hr style="margin: .5em -15px;border-color:#ccc" />

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
                        <button type="submit" class="btn btn-md btn-primary float-right">Simpan Order</a>
                    </div>
                </div>

                <div class="product-list">
                    @include('admin.orders.parts.item-product', [
                        'detail' => new App\Models\OrderDetail,
                        'modal' => $item['modal'],
                        'name' => $item['name'],
                    ])

                    @if ($item['items']->count())
                        @php
                            $sortedProducts = $item['items']->sortBy('tipe_pg')->sortBy('halaman_id')->sortBy('kelas_id')->sortBy('product.name')->sortBy('product.jenjang_id');
                        @endphp
                        @each('admin.orders.parts.item-product', $sortedProducts, 'detail')
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
                    @include('admin.orders.parts.item-product', [
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
                        <strong class="product-total">@money(data_get($order, 'tagihan.total', 0))</strong>
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
                {{-- <button type="submit" class="btn {{ !$order->id ? 'btn-primary' : 'btn-secondary' }}">Simpan Order</a> --}}
                <button type="submit" class="btn btn-primary">Simpan Order</a>
            </div>
        </div>
    </form>
</div>

@push('footer')
<!-- Modal Products -->
@foreach ([
    [
        'id' => 'productModal',
        'label' => 'Semua Bahan',
        'items' => $products,
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

                    <div class="product-select" style="display: {{ !$products->count() ? 'none' : 'block' }}">
                        @foreach ($modal['items'] as $product)
                            @php
                            $category = $product->category;
                            $cover = $product->brand;
                            $isi = $product->isi;
                            $jenjang = $product->jenjang;
                            $semester = $product->semester;
                            $kelas = $product->kelas;
                            $halaman = $product->halaman;
                            $search = implode(';', [
                                $product->nama_buku,
                                !$category ? '' : $category->name,
                                !$cover ? '' : $cover->name,
                                !$isi ? '' : $isi->name,
                                !$jenjang ? '' : $jenjang->name,
                                !$semester ? '' : $semester->name,
                                !$kelas ? '' : 'KELAS '.$kelas->name,
                                !$halaman ? '' : 'HAL '.$halaman->name,
                            ]);
                            $selected = $order->order_details->where('product_id', $product->id)->count();
                            @endphp
                            <a
                                href="{{ route('admin.products.show', $product->id) }}"
                                class="product-select-item{{ $selected ? ' selected' : '' }}"
                                data-search="{{ strtolower($search) }}"
                                data-id="{{ $product->id }}"
                                data-price="{{ $product->price }}"
                                data-hpp="{{ $product->hpp }}"
                                data-stock="{{ $product->stock }}"
                                data-pg="{{ $product->tipe_pg }}"
                                data-jenjang="{{ $product->jenjang_id }}"
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
                                                HPP: <span class="product-hpp">@money($product->hpp)</span>
                                            </p>

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

<div class="modal fade" id="gantiHargaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Ubah Harga</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{ route('admin.orders.change_price') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="order_id" value="{{ $order->id ?? '' }}">
                    <div class='row'>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="harga_awal" class="control-label">Harga Awal</label>
                                <div>
                                    <input type="number" class="form-control" id="harga_awal"  name="harga_awal" step="10" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="harga_koreksi" class="control-label">Harga Koreksi</label>
                                <div>
                                    <input type="number" class="form-control" id="harga_koreksi"  name="harga_koreksi" step="10" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="hal_harga" class="control-label">Halaman</label>
                                <div>
                                    <select class="form-control select2" name="hal_harga" id="hal_harga" required>
                                        @foreach($halaman as $id => $entry)
                                            <option value="{{ $id }}">Halaman {{ $entry }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Ubah</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
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
        var form = $('#orderForm');

        var orderProduct = form.find('.order-product');
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
                var pg= product.find('.product-pg');
                var bonus = product.find('.product-bonus');

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

                qty.add(price).on('change keyup blur', function(e) {
                    calculatePrice();
                });

                qty.on('keyup blur', function(e) {
                    let jenjang_id = product.attr('data-jenjang');
                    let pembagi = 100;
                    if(jenjang.includes(jenjang_id)){
                        pembagi = 33.34;
                    }
                    let bonus_qty = Math.ceil(qty.val()/pembagi);
                    bonus.val(bonus_qty).trigger('change');
                });

                pg.on('change', function(e) {
                    var value = e.target.value;
                }).trigger('change');

                priceText.on('change keyup blur', function(e) {
                    var value = numeral(e.target.value);

                    priceText.val(value.format('0,0'));
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
                product.prependTo(products);

                console.log('ini product add');

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
            var qty = product.find('.product-qty').val();
            var price = product.find('.product-price').val();
            var bonus = product.find('.product-bonus').val();
            var name = product.data('name');
            var data = selected.data();

            product.attr('data-id', data.id).data('id', data.id);
            product.attr('data-price', data.price).data('price', data.price);
            product.attr('data-stock', data.stock).data('stock', data.stock);
            product.attr('data-jenjang', data.jenjang).data('stock', data.jenjang);
            product.find('.product-col-main').html(content);
            product.find('.product-qty1').val(qty || 0)
                .attr('id', 'fieldQty-'+data.id)
                .attr('name', name+'['+data.id+'][qty]')
                .attr('min', 1)
                .attr('required', true);
            product.find('.product-price').val(price != 0 ? price : data.price)
                .attr('name', name+'['+data.id+'][price]');
            product.find('.product-price_text').val(price != 0 ? price : data.price)
                .attr('id', 'fieldPrice-'+data.id)
                .attr('name', name+'['+data.id+'][price_text]')
                .attr('min', 1)
                .attr('required', true)
                .trigger('change');


            if (data.pg !== 'non_pg') {
                product.find('.div-product-pg').hide();
                product.find('.div-product-bonus').hide();
            } else {
                product.find('.div-product-pg').show();
                product.find('.div-product-bonus').show();
                product.find('.product-pg')
                    .attr('id', 'fieldPg-'+data.id)
                    .attr('name', name+'['+data.id+'][pg]')
                    .attr('required', true)
                    .trigger('change');
                product.find('.product-bonus').val(bonus || 0)
                    .attr('id', 'fieldBonus-'+data.id)
                    .attr('name', name+'['+data.id+'][bonus]')
                    .attr('min', 0)
                    .attr('required', true);
            }

            product.find('.product-subtotal').html(numeral(data.price).format('$0,0'));
            product.find('.product-img').attr('src', data.image).parent()[!data.image ? 'hide' : 'show']();

            // productSearchClear.trigger('click');
            modals.modal('hide');
            selected.addClass('selected');
            productSummary.show();
            calculatePrice();

            product.closest('.product-list-group').find('.product-add').trigger('click');
        });
    });
})(jQuery, window.numeral);
</script>
<script>
$(document).ready(function() {
    $('#salesperson_id').change(function() {
        $('#kota_sales_id').empty();
        let salesId = $(this).val();
        if (salesId) {
            $('#kota_sales_id').select2({
                allowClear: true,
                placeholder: "Pilih Kota",
                ajax: {
                    url: "{{ route('admin.salespeople.select') }}?sales=" + salesId,
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(key, value) {
                                return {
                                    text: key,
                                    id: value
                                }
                            })
                        };
                    }
                }
            });
        } else {
            $('#kota_sales_id').empty();
        }
    });
    $('#synchronFaktur').on('click', function(e) {
        e.preventDefault();

        var el = $(e.currentTarget);
        var url = el.attr('href');
        var order = el.data('order');
        console.log(order);
        if (confirm('{{ trans('global.areYouSure') }}')) {
            $.ajax({
                headers: {'x-csrf-token': _token},
                method: 'POST',
                url: url,
                data: {
                    order
                }
            }).done(function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Harga berhasil diubah',
                    showConfirmButton: true,
                    timer: 1500
                }).then((result) => {
                    location.reload();
                });
            });
        }
    });
});
</script>
@endpush
