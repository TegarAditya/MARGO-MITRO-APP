@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        @if (!$invoice->id)
            Tambah Invoice
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

        <form method="POST" action="{{ !$invoice->id ? route('admin.invoices.store') : route("admin.invoices.update", [$invoice->id]) }}" enctype="multipart/form-data" id="invoiceForm">
            @method(!$invoice->id ? 'POST' : 'PUT')
            @csrf

            <div class="form-group">
                <label class="required" for="no_suratjalan">{{ trans('cruds.invoice.fields.no_suratjalan') }}</label>
                <input class="form-control {{ $errors->has('no_suratjalan') ? 'is-invalid' : '' }}" type="text" name="no_suratjalan" id="no_suratjalan" value="{{ old('no_suratjalan', $invoice->no_suratjalan) }}" readonly placeholder="(Otomatis)">
                @if($errors->has('no_suratjalan'))
                    <span class="text-danger">{{ $errors->first('no_suratjalan') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.invoice.fields.no_suratjalan_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="no_invoice">{{ trans('cruds.invoice.fields.no_invoice') }}</label>
                <input class="form-control {{ $errors->has('no_invoice') ? 'is-invalid' : '' }}" type="text" name="no_invoice" id="no_invoice" value="{{ old('no_invoice', $invoice->no_invoice) }}" readonly placeholder="(Otomatis)">
                @if($errors->has('no_invoice'))
                    <span class="text-danger">{{ $errors->first('no_invoice') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.invoice.fields.no_invoice_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="order_id">{{ trans('cruds.invoice.fields.order') }}</label>
                <select class="form-control select2 {{ $errors->has('order') ? 'is-invalid' : '' }}" name="order_id" id="order_id" required>
                    @foreach($orders as $id => $entry)
                        <option value="{{ $id }}" {{ (old('order_id') ? old('order_id') : $invoice->order->id ?? '') == $id ? 'selected' : (
                            request('order_id') == $id ? 'selected' : ''
                        ) }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('order'))
                    <span class="text-danger">{{ $errors->first('order') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.invoice.fields.order_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="date">{{ trans('cruds.invoice.fields.date') }}</label>
                <input class="form-control date {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="date" id="date" value="{{ old('date', $invoice->date) }}" required>
                @if($errors->has('date'))
                    <span class="text-danger">{{ $errors->first('date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.invoice.fields.date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="nominal">{{ trans('cruds.invoice.fields.nominal') }}</label>
                <input class="form-control {{ $errors->has('nominal') ? 'is-invalid' : '' }}" type="number" name="nominal" id="nominal" value="{{ old('nominal', $invoice->nominal) }}" step="1">
                @if($errors->has('nominal'))
                    <span class="text-danger">{{ $errors->first('nominal') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.invoice.fields.nominal_helper') }}</span>
            </div>

            <hr class="my-3" />

            <h5 class="pt-2">Detail Faktur</h5>

            @php
            $tabs = [
                [ 'label' => 'Daftar Produk', 'enabled' => true ],
                [ 'label' => 'Riwayat', 'enabled' => $order ],
            ];
            @endphp
            <ul class="nav nav-tabs" id="invoiceTabs" role="tablist">
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

            <div class="tab-content" id="invoiceTabsContent">
                <div class="tab-pane fade show active" id="order-1" role="tabpanel">
                    @include('admin.invoices.parts.tab-product')
                </div>

                <div class="tab-pane fade" id="order-2" role="tabpanel">
                    @include('admin.invoices.parts.tab-faktur')
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
        var form = $('#invoiceForm');
        var tabs = form.find('#invoiceTabs');
        var tabsContent = form.find('#invoiceTabsContent');
        var tabsNavs = form.find('.invoiceTabs-nav');

        tabsNavs.on('click', function(e) {
            e.preventDefault();

            tabs.find('[href="'+$(e.currentTarget).attr('href')+'"]').filter(':not(.disabled)').tab('show');
        });
    });
})(jQuery);
</script>
@endpush
