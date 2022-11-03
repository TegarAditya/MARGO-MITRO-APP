@extends('layouts.frontend')
@section('content')
<div class="container">
    <form method="POST" action="{{ route("frontend.production-orders.process", [$productionOrder->id]) }}" enctype="multipart/form-data" id="modelForm">
        @method('POST')
        @csrf

        <input type="hidden" name="updated_at" value="{{ $productionOrder->updated_at->format('Y-m-d H:i:s') }}" />

        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        Production Order
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <p class="mb-0">No. PO</p>

                                <h5 class="mb-0">
                                    {{ $productionOrder->no_order }}
                                </h5>
                            </div>

                            <div class="col-6 text-right">
                                <p class="mb-0">Tanggal</p>

                                <p class="mb-0">
                                    {{ $productionOrder->date }}
                                </p>
                            </div>

                            <div class="col-6">
                                <p class="mb-0">Pelaksana</p>

                                <p class="mb-0">
                                    <strong>{{ $productionOrder->productionperson->name }}</strong>
                                </p>
                            </div>
                        </div>

                        @php
                        $tabs = [
                            [ 'label' => 'File Mentah', 'enabled' => true ],
                            [ 'label' => 'Cetak Plate', 'enabled' => !!$productionOrder->id ],
                            [ 'label' => 'Ambil Plate', 'enabled' => !!$productionOrder->id ],
                        ];
                        @endphp
                        <ul class="nav nav-tabs mt-4" id="modelTabs" role="tablist">
                            @foreach ($tabs as $tab)
                                @php
                                $classes = $loop->first ? ' active' : '';
            
                                if (!$tab['enabled']) {
                                    $classes .= ' disabled';
                                }
                                @endphp
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link{{ $classes }}" id="tab-{{ $loop->iteration }}" data-toggle="tab" href="#model-tab-{{ $loop->iteration }}" role="tab">
                                        {{ $loop->iteration . '. ' . $tab['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
            
                        <div class="tab-content" id="modelTabsContent">
                            <div class="tab-pane fade show active" id="model-tab-1" role="tabpanel">
                                @include('frontend.productionOrders.parts.process-tab-file')
                            </div>

                            <div class="tab-pane fade" id="model-tab-2" role="tabpanel">
                                @include('frontend.productionOrders.parts.process-tab-plate')
                            </div>

                            <div class="tab-pane fade" id="model-tab-3" role="tabpanel">
                                @include('frontend.productionOrders.parts.process-tab-plate-ambil')
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
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