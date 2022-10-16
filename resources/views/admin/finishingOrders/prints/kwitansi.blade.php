@extends('layouts.print')

@section('header.right')
<h6>KWITANSI PO</h6>

<table cellspacing="0" cellpadding="0" class="text-sm" style="width: 9cm">
    <tbody>
        <tr>
            <td width="136"><strong>No. PO</strong></td>
            <td width="12">:</td>
            <td>{{ $finishingOrder->po_number }}</td>
        </tr>

        <tr>
            <td width="120"><strong>No. Kwitansi</strong></td>
            <td width="8">:</td>
            <td>{{ $finishingOrder->no_kwitansi }}</td>
        </tr>

        <tr>
            <td><strong>Tanggal</strong></td>
            <td>:</td>
            <td>{{ $finishingOrder->date }}</td>
        </tr>

        <tr>
            <td><strong>Subkontraktor</strong></td>
            <td>:</td>
            <td>{{ $finishingOrder->productionperson->name }}</td>
        </tr>
    </tbody>
</table>
@stop

@section('content')
<table cellspacing="0" cellpadding="0" class="table table-sm table-bordered" style="width: 100%">
    <thead>
        <th width="1%">No.</th>
        <th>Jenjang - Kelas</th>
        <th>Tema/Mapel</th>
        <th width="1%" class="text-center">Hal</th>
        <th width="15%" class="text-center">Harga</th>
        <th width="120" class="text-center">Order Qty</th>
        <th width="20%" class="text-center">Subtotal</th>
    </thead>

    <tbody>
        @foreach ($finishingOrder->finishing_order_details as $detail)
            @php
            $product = $detail->product;
            $selisih = $detail->order_qty - $detail->prod_qty;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $product->jenjang->name ?? '' }} - Kelas {{ $product->kelas->name ?? '' }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->hal->name ?? '' }}</td>
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
            <td colspan="6" class="text-center px-3"><strong>Total</strong></td>
            <td class="text-right">@money(abs($finishingOrder->total))</td>
        </tr>
    </tfoot>
</table>
@endsection

@section('footer')
<div class="row">
    <div class="col align-self-end">
        <p class="mb-2">Dikeluarkan oleh,</p>
        <p class="mb-0">Margo Mitro Joyo</p>
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
