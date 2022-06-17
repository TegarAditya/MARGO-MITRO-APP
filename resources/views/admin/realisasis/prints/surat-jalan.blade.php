@extends('layouts.print')

@section('header.right')
<h6>SURAT JALAN</h6>

<table cellspacing="0" cellpadding="0" class="text-sm" style="width: 9cm">
    <tbody>
        <tr>
            <td width="136"><strong>No. Invoice</strong></td>
            <td width="12">:</td>
            <td>{{ $realisasi->no_invoice }}</td>
        </tr>

        <tr>
            <td width="120"><strong>No. Surat Jalan</strong></td>
            <td width="8">:</td>
            <td>{{ $realisasi->no_suratjalan }}</td>
        </tr>

        <tr>
            <td><strong>Tanggal</strong></td>
            <td>:</td>
            <td>{{ $realisasi->date }}</td>
        </tr>

        <tr>
            <td><strong>{{ $realisasi->type === 'Masuk' ? 'Dari' : 'Kepada' }}</strong></td>
            <td>:</td>
            <td style="border-bottom: 1px dotted #000"></td>
        </tr>
    </tbody>
</table>
@stop

@section('content')
<table cellspacing="0" cellpadding="0" class="table table-sm table-bordered" style="width: 100%">
    <thead>
        <th class="px-3" width="1%">No.</th>
        <th class="px-3">Nama Produk</th>
        <th class="px-3" width="1%">Qty</th>
    </thead>

    <tbody>
        @foreach ($realisasi->realisasi_details as $realisasi_detail)
            @php
            $product = $realisasi_detail->product;
            @endphp
            <tr>
                <td class="px-3">{{ $loop->iteration }}</td>
                <td class="px-3">{{ $product->name }}</td>
                <td class="px-3 text-center">{{ abs($realisasi_detail->quantity) }}</td>
            </tr>
        @endforeach
    </tbody>
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
