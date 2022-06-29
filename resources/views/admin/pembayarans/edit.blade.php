@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.pembayaran.title_singular') }}
    </div>

    <div class="card-body">
        @if ($order->id)
            <div class="row mb-4">
                <div class="col-6">
                    @if ($pembayaran->id)
                        <h4>Pembayaran No. #{{ $pembayaran->no_kwitansi }}</h4>
                    @endif

                    <div class="row">
                        <div class="col-auto">
                            <span class="text-xs">Order No.</span>
                            <p class="m-0">
                                <a href="{{ route('admin.orders.edit', $order->id) }}">#{{ $order->no_order }}</a>
                            </p>
                        </div>

                        <div class="col-auto">
                            <span class="text-xs">Sales Person</span>
                            <p class="m-0">
                                <strong>{{ $order->salesperson->name ?? '-' }}</strong>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-6 text-right">
                    <h6 class="h6 text-primary m-0">{{ $order->date }}</h6>
                </div>
            </div>
        @endif

        @if (session()->has('error-message'))
            <p class="text-danger">
                {{session()->get('error-message')}}
            </p>
        @endif

        <form method="POST" action="{{ !$pembayaran->id ? route('admin.pembayarans.store') : route("admin.pembayarans.update", [$pembayaran->id]) }}" enctype="multipart/form-data" id="pembayaranForm">
            @method(!$pembayaran->id ? 'POST' : 'PUT')
            @csrf

            @if ($order_id = request('order_id', $tagihan->order_id))
                <input type="hidden" name="redirect" value="{{ route('admin.orders.edit', $order_id) }}" />
            @endif

            @php
            $tabs = [
                [ 'label' => 'Pembayaran', 'enabled' => true ],
                [ 'label' => 'Riwayat', 'enabled' => !!$tagihan->id ],
            ];
            @endphp
            <ul class="nav nav-tabs" id="modelTabs" role="tablist">
                @foreach ($tabs as $tab)
                    @php
                    $classes = $loop->first ? ' active' : '';

                    if (!$tab['enabled']) {
                        $classes .= ' disabled';
                    }
                    @endphp
                    <li class="nav-item" role="presentation">
                        <a class="nav-link{{ $classes }}" id="order-tab-{{ $loop->iteration }}" data-toggle="tab" href="#order-{{ $loop->iteration }}" role="tab">
                            {{ $loop->iteration . '. ' . $tab['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content" id="modelTabsContent">
                <div class="tab-pane fade show active" id="order-1" role="tabpanel">
                    @include('admin.pembayarans.parts.tab-pembayaran')
                </div>

                <div class="tab-pane fade" id="order-2" role="tabpanel">
                    @include('admin.pembayarans.parts.tab-pembayaran-history')
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function($) {
    $(function() {
        var form = $('#pembayaranForm');
        var tabs = form.find('#modelTabs');
        var tabsContent = form.find('#modelTabsContent');
        var tabsNavs = form.find('.modelTabs-nav');

        tabsNavs.on('click', function(e) {
            e.preventDefault();

            tabs.find('[href="'+$(e.currentTarget).attr('href')+'"]').filter(':not(.disabled)').tab('show');
        });
    });
})(jQuery);
</script>
@endpush
