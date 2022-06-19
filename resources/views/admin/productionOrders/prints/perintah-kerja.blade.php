@extends('layouts.print')

@section('header.right')
<h6>SURAT PERINTAH KERJA</h6>

<table cellspacing="0" cellpadding="0" class="text-sm" style="width: 9cm">
    <tbody>
        <tr>
            <td width="136"><strong>No. PO</strong></td>
            <td width="12">:</td>
            <td>{{ $productionOrder->po_number }}</td>
        </tr>

        <tr>
            <td width="120"><strong>No. SPK</strong></td>
            <td width="8">:</td>
            <td>{{ $productionOrder->no_spk }}</td>
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
@if ($person = $productionOrder->productionperson)
    <p class="mb-3">
        Pelaksana: <strong>{{ $person->name }}</strong>
    </p>
@endif

<table cellspacing="0" cellpadding="0" class="table table-sm table-bordered" style="width: 100%">
    <thead>
        <th width="1%">No.</th>
        <th>Nama Produk</th>
        <th class="text-center" width="120">Order Qty</th>
    </thead>

    <tbody>
        @foreach ($productionOrder->production_order_details as $detail)
            @php
            $product = $detail->product;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $product->name }}</td>
                <td class="text-center">{{ abs($detail->order_qty - $detail->prod_qty) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection

@section('footer')
<div class="row">
    <div class="col-auto align-self-end">
        <p class="mb-2">Dikeluarkan oleh,</p>
        <p class="mb-0">Gudang</p>
    </div>

    <div class="col"></div>

    <div class="col-auto text-center">
        <p class="mb-5">Pelaksana</p>
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
