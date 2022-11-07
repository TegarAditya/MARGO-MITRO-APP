@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        @if (!$realisasi->id)
            Tambah Realisasi
        @else
            Edit Invoice
        @endif
    </div>

    <div class="card-body">
        @if ($productionOrder->id)
            <div class="row mb-4">
                <div class="col">
                    @if ($realisasi->id)
                        <h4>
                            No. Realisasi #{{ $realisasi->no_realisasi }}
                        </h4>
                    @endif

                    <div class="row">
                        <div class="col-auto">
                            <span class="text-xs">PO No. </span>
                            <p class="m-0">
                                <strong>
                                    <a href="{{ route('admin.production-orders.edit', $productionOrder->id) }}">
                                        #{{ $productionOrder->po_number }}
                                    </a>
                                </strong>
                            </p>
                        </div>

                        <div class="col-auto">
                            <span class="text-xs">Production Person</span>
                            <p class="m-0">
                                <strong>{{ $productionOrder->productionperson->name ?? '-' }}</strong>
                            </p>
                        </div>

                        <div class="col-auto ml-3">
                            <span class="text-xs">Jenis</span>
                            <p class="m-0">
                                <strong>{{ ucfirst($productionOrder->type) }}</strong>
                            </p>
                        </div>

                        <div class="col-auto d-flex align-items-center ml-3">
                            <a href="{{ route('admin.production-orders.show', [
                                'production_order' => $productionOrder->id,
                                'print' => 'spk'
                            ]) }}" target="_blank" title="Cetak SPK" class="btn btn-sm btn-default border py-0 px-1">
                                <i class="fa fa-print text-info"></i>
                            </a>

                            <div class="col text-muted px-2">
                                <span class="text-xs">No. SPK</span>
                                <p class="m-0">{{ $productionOrder->no_spk }}</p>
                            </div>
                        </div>

                        <div class="col-auto ml-3 d-flex align-items-center">
                            <a href="{{ route('admin.production-orders.show', [
                                'production_order' => $productionOrder->id,
                                'print' => 'kwitansi'
                            ]) }}" target="_blank" title="Cetak Kwitansi" class="btn btn-sm btn-default border py-0 px-1">
                                <i class="fa fa-print text-info"></i>
                            </a>

                            <div class="col text-muted px-2">
                                <span class="text-xs">No. Kwitansi</span>
                                <p class="m-0">{{ $productionOrder->no_kwitansi }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-auto text-right">
                    <h6 class="h6 text-primary m-0">{{ $productionOrder->date }}</h6>
                </div>
            </div>
        @endif

        @if (session()->has('error-message'))
            <p class="text-danger">
                {{session()->get('error-message')}}
            </p>
        @endif

        <form method="POST" action="{{ !$realisasi->id ? route('admin.realisasis.store') : route("admin.realisasis.update", [$realisasi->id]) }}" enctype="multipart/form-data" id="modelForm">
            @method(!$realisasi->id ? 'POST' : 'PUT')
            @csrf

            @if ($production_order_id = request('production_order_id', $realisasi->production_order_id))
                <input type="hidden" name="redirect" value="{{ route('admin.production-orders.edit', $production_order_id) }}" />
            @endif

            @php
            $tabs = [
                [ 'label' => 'Faktur', 'enabled' => true ],
                [ 'label' => 'Riwayat', 'enabled' => !!$production_order_id ],
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
                        <a class="nav-link{{ $classes }}" id="tab-{{ $loop->iteration }}" data-toggle="tab" href="#model-tab-{{ $loop->iteration }}" role="tab">
                            {{ $loop->iteration . '. ' . $tab['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content" id="modelTabsContent">
                <div class="tab-pane fade show active" id="model-tab-1" role="tabpanel">
                    @include('admin.realisasis.parts.tab-realisasi')
                </div>

                @if ($production_order_id)
                    <div class="tab-pane fade" id="model-tab-2" role="tabpanel">
                        @include('admin.realisasis.parts.tab-realisasi-history')
                    </div>
                @endif
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
