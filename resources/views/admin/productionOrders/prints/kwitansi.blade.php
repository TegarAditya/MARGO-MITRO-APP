@extends('layouts.print')

@section('header.right')
<h6>KWITANSI PO</h6>

<table cellspacing="0" cellpadding="0" class="text-sm" style="width: 9cm">
    <tbody>
        <tr>
            <td width="136"><strong>No. PO</strong></td>
            <td width="12">:</td>
            <td>{{ $productionOrder->po_number }}</td>
        </tr>

        <tr>
            <td width="120"><strong>No. Kwitansi</strong></td>
            <td width="8">:</td>
            <td>{{ $productionOrder->no_kwitansi }}</td>
        </tr>

        <tr>
            <td><strong>Tanggal</strong></td>
            <td>:</td>
            <td>{{ $productionOrder->date }}</td>
        </tr>
    </tbody>
</table>
@stop

@section('content')
<table cellspacing="0" cellpadding="0" class="table table-sm table-bordered" style="width: 100%">
    <thead>
        <th width="1%">No.</th>
        <th>Nama Produk</th>
        <th width="1%" class="text-center">Harga</th>
        <th width="120" class="text-center">Order Qty</th>
        <th width="1%" class="text-center">Subtotal</th>
    </thead>

    <tbody>
        @foreach ($productionOrder->production_order_details as $detail)
            @php
            $product = $detail->product;
            $selisih = $detail->order_qty - $detail->prod_qty;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $product->name }}</td>
                <td class="text-right">@money($detail->ongkos_satuan)</td>
                <td class="text-center text-nowrap">
                    {{ abs($selisih < 0 ? 0 : $selisih) }}{{ $selisih >= 0 ? '' : " (+".abs($selisih).")" }}
                </td>
                <td class="text-right">@money(abs($detail->ongkos_total))</td>
            </tr>
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <td colspan="4" class="text-right px-3"><strong>Total</strong></td>
            <td>@money(abs($productionOrder->total))</td>
        </tr>
    </tfoot>
</table>
@endsection

@section('footer')
<div class="row">
    <div class="col align-self-end">
        <p class="mb-2">Dikeluarkan oleh,</p>
        <p class="mb-0">Gudang</p>
    </div>

    <div class="col-auto text-center">
        <p class="mb-5">Pengirim</p>
        <p class="mb-0">( _____________ )</p>
    </div>

    <div class="col-auto text-center">
        <p class="mb-5">Penerima</p>
        <p class="mb-0">( _____________ )</p>
    </div>
</div>
@endsection

@push('styles')
<style type="text/css" media="print">
@page {
    size: landscape;
}
</style>
@endpush
