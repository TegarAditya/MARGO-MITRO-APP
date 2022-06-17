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
        @if (session()->has('error-message'))
            <p class="text-danger">
                {{session()->get('error-message')}}
            </p>
        @endif

        <form method="POST" action="{{ !$realisasi->id ? route('admin.realisasis.store') : route("admin.realisasis.update", [$realisasi->id]) }}" enctype="multipart/form-data" id="modelForm">
            @method(!$realisasi->id ? 'POST' : 'PUT')
            @csrf

            @if ($production_order_id = request('production_order_id'))
                <input type="hidden" name="redirect" value="{{ route('admin.production-orders.edit', $production_order_id) }}" />
            @endif

            @php
            $tabs = [
                [ 'label' => 'Faktur', 'enabled' => true ],
                [ 'label' => 'Riwayat', 'enabled' => $realisasi ],
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

                <div class="tab-pane fade" id="model-tab-2" role="tabpanel">
                    {{-- @include('admin.realisasis.parts.tab-realisasi-history') --}}
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
