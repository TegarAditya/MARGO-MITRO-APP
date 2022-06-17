@extends('layouts.print')

@section('header.right')
<h6>FAKTUR</h6>

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
        <th width="1%">No.</th>
        <th>Nama Produk</th>
        <th width="1%">Harga</th>
        <th width="1%">Qty</th>
        <th width="1%">Subtotal</th>
    </thead>

    <tbody>
        @foreach ($realisasi->realisasi_details as $realisasi_detail)
            @php
            $product = $realisasi_detail->product;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $product->name }}</td>
                <td class="text-right">@money($realisasi_detail->price)</td>
                <td class="text-center">{{ abs($realisasi_detail->quantity) }}</td>
                <td class="text-right">@money(abs($realisasi_detail->total))</td>
            </tr>
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <td colspan="4" class="text-right px-3"><strong>Total</strong></td>
            <td>@money(abs($realisasi->nominal))</td>
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
