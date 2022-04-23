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
        @if (session()->has('error-message'))
            <p class="text-danger">
                {{session()->get('error-message')}}
            </p>
        @endif

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
            </div>

            <div class="row">
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

                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="salesperson_id">{{ trans('cruds.order.fields.salesperson') }}</label>
                        <select class="form-control select2 {{ $errors->has('salesperson') ? 'is-invalid' : '' }}" name="salesperson_id" id="salesperson_id" required>
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
            </div>

            <hr class="my-3" />

            <h5 class="pt-2">Detail Order</h5>

            @php
            $tabs = [
                [ 'label' => 'Daftar Produk', 'enabled' => true ],
                [ 'label' => 'Tagihan', 'enabled' => !!$order->id ],
                [ 'label' => 'Faktur', 'enabled' => !!$order->id ],
            ];
            @endphp
            <ul class="nav nav-tabs" id="orderTabs" role="tablist">
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

            <div class="tab-content" id="orderTabsContent">
                <div class="tab-pane fade show active" id="order-1" role="tabpanel">
                    @include('admin.orders.parts.tab-product')
                </div>

                <div class="tab-pane fade" id="order-2" role="tabpanel">
                    @include('admin.orders.parts.tab-tagihan')
                </div>

                <div class="tab-pane fade" id="order-3" role="tabpanel">
                    @include('admin.orders.parts.tab-faktur')
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
