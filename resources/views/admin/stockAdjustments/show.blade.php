@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.order.title') }}
    </div>

    <div class="card-body">
        <div class="row">

            <div class="col">
                <a class="btn btn-default" href="{{ url()->previous() }}">
                    Back
                </a>
            </div>

            <div class="col-auto">
                <a class="btn btn-info" href="{{ route('admin.stock-adjustments.edit', $stockAdjustment->id) }}">
                    Edit Adjustment
                </a>
            </div>
        </div>

        <div class="model-detail mt-3">

            <section class="py-3" id="modelDetail">
                <h6>Detail Adjustment</h6>

                <table class="table table-sm border m-0">
                    <tbody>
                        <tr>
                            <th width="150">
                                Operation
                            </th>
                            <td>
                                {{ App\Models\StockAdjustment::OPERATION_SELECT[$stockAdjustment->operation] ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.date') }}
                            </th>
                            <td>
                                {{ $stockAdjustment->date }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.stockAdjustment.fields.note') }}
                            </th>
                            <td>
                                {{ $stockAdjustment->note ?? '' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <section class="border-top py-3">
                <div class="row mb-2">
                    <div class="col">
                        <h6>Daftar Produk</h6>

                        <p class="mb-0">Total Produk: {{ $stockAdjustment->details->count() }}</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body px-3 py-2">
                        <table class="table table-sm table-bordered m-0">
                            <thead>
                                <tr>
                                    <th class="text-center" width="1%">No.</th>
                                    <th>Nama Produk</th>
                                    <th class="text-center px-3" width="1%">Quantity</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($stockAdjustment->details as $detail)
                                    @php
                                    $product = $detail->product;
                                    @endphp
                                    <tr>
                                        <td class="text-right px-3">{{ $loop->iteration }}.</td>
                                        <td>{{ $product->nama_isi_buku }}</td>
                                        <td class="text-center px-3">{{ abs($detail->quantity) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function($) {
    $(function() {
        var maxScroll = $(document).height() - $(window).height();
        var detail = $('.model-detail');
        var nav = $('.breadcrumb-nav');
        var navHi = nav.height();
        var sections = detail.children('section');
        var tops = sections.map(function (index, item) {
            return $(item).offset().top;
        });

        $(window).on('scroll', function(e) {
            var scroll = e.currentTarget.scrollY + navHi;
            var section;

            tops.map(function(index, item) {
                if (scroll >= item) {
                    section = sections.eq(index);
                }
            });

            if (scroll >= maxScroll) {
                section = sections.eq(tops.length - 1);
            }

            if (section) {
                var id = section.attr('id');
                var navLink = nav.find('a[href="#'+id+'"]');

                nav.find('a').removeClass('active');
                navLink.length && navLink.addClass('active');
            }
        });

        nav.find('a').on('click', function(e) {
            e.preventDefault();

            var el = $(e.currentTarget);
            var href = el.attr('href');
            var target = $(href);

            target.length && $('html, body').animate({
                scrollTop: target.offset().top - nav.height()
            }, 500, 'linear');
        });
    });
})(jQuery);
</script>
@endpush
