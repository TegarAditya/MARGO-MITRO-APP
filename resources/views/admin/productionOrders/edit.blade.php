@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        @if (!$productionOrder->id)
            Tambah Order
        @else
            Edit Order
        @endif
    </div>

    <div class="card-body">
        @if (session()->has('error-message'))
            <p class="text-danger">
                {{session()->get('error-message')}}
            </p>
        @endif

        <form method="POST" action="{{ !$productionOrder->id ? route('admin.orders.store') : route("admin.orders.update", [$productionOrder->id]) }}" enctype="multipart/form-data" id="modelForm">
            @method(!$productionOrder->id ? 'POST' : 'PUT')
            @csrf

            @php
            $tabs = [
                [ 'label' => 'Detail Order', 'enabled' => true ],
                [ 'label' => 'Kwitansi', 'enabled' => !!$productionOrder->id ],
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
                        <a class="nav-link{{ $classes }}" id="order-tab-{{ $loop->iteration }}" data-toggle="tab" href="#model-tab-{{ $loop->iteration }}" role="tab">
                            {{ $loop->iteration . '. ' . $tab['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content" id="modelTabsContent">
                <div class="tab-pane fade show active" id="model-tab-1" role="tabpanel">
                    @include('admin.productionOrders.parts.tab-order')
                </div>

                <div class="tab-pane fade" id="model-tab-3" role="tabpanel">
                    @include('admin.productionOrders.parts.tab-kwitansi')
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
        var form = $('#modelForm');
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
