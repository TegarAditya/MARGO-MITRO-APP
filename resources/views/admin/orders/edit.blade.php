@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        @if (!$order->id)
            Tambah Order
        @else
            Edit Order
        @endif
    </div>

    <div class="card-body">
        @if ($order->id)
            <div class="row mb-4">
                <div class="col-6">
                    <h4>Order No. #{{ $order->no_order }}</h4>

                    <div class="row">
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

        <form method="POST" action="{{ !$order->id ? route('admin.orders.store') : route("admin.orders.update", [$order->id]) }}" enctype="multipart/form-data" id="orderForm">
            @method(!$order->id ? 'POST' : 'PUT')
            @csrf

            @php
            $tabs = [
                [ 'id' => 'order', 'label' => 'Detail Order', 'enabled' => true ],
                [ 'id' => 'faktur', 'label' => 'Faktur', 'enabled' => !!$order->id ],
                [ 'id' => 'pembayaran', 'label' => 'Pembayaran', 'enabled' => !!$order->id ],
            ];

            if (!isset($activeTabs)) {
                $activeTabs = 'faktur';
            }
            @endphp
            <ul class="nav nav-tabs" id="orderTabs" role="tablist">
                @foreach ($tabs as $tab)
                    @php
                    $classes = '';

                    if (!$tab['enabled']) {
                        $classes .= ' disabled';
                    }
                    @endphp
                    <li class="nav-item" role="presentation">
                        <a class="nav-link{{ $classes }} {{ $activeTabs === $tab['id'] ? ' active' : '' }}" id="order-tab-{{ $loop->iteration }}" data-toggle="tab" href="#order-{{ $loop->iteration }}" role="tab">
                            {{ $loop->iteration . '. ' . $tab['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content" id="orderTabsContent">
                <div class="tab-pane fade{{ $activeTabs === 'order' ? ' show active' : '' }}" id="order-1" role="tabpanel">
                    @include('admin.orders.parts.tab-order')
                </div>

                <div class="tab-pane fade{{ $activeTabs === 'faktur' ? ' show active' : '' }}" id="order-2" role="tabpanel">
                    @include('admin.orders.parts.tab-faktur')
                </div>

                <div class="tab-pane fade{{ $activeTabs === 'pembayaran' ? ' show active' : '' }}" id="order-3" role="tabpanel">
                    @include('admin.orders.parts.tab-tagihan')
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
        var form = $('#orderForm');
        var tabs = form.find('#orderTabs');
        var tabsContent = form.find('#orderTabsContent');
        var tabsNavs = form.find('.orderTabs-nav');

        tabsNavs.on('click', function(e) {
            e.preventDefault();

            tabs.find('[href="'+$(e.currentTarget).attr('href')+'"]').filter(':not(.disabled)').tab('show');
        });
    });
})(jQuery);
</script>
@endpush
