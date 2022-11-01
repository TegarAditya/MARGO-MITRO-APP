@extends('layouts.print')

@section('header.center')
<h6>FAKTUR {{ $invoice->type === 'Masuk' ? 'RETURN' : '' }}</h6>
@endsection

@section('header.left')
<table cellspacing="0" cellpadding="0" class="text-sm" style="width: 10cm">
    <tbody>
        <tr>
            <td width="136"><strong>No. Invoice</strong></td>
            <td width="12">:</td>
            <td>{{ $invoice->no_invoice }}</td>
        </tr>

        <tr>
            <td width="120"><strong>No. Surat Jalan</strong></td>
            <td width="8">:</td>
            <td>{{ $invoice->no_suratjalan }}</td>
        </tr>

        <tr>
            <td><strong>Tanggal</strong></td>
            <td>:</td>
            <td>{{ Carbon\Carbon::parse($invoice->date)->format('d-m-Y') }}</td>
        </tr>
    </tbody>
</table>
@stop

@section('header.right')
<table cellspacing="0" cellpadding="0" class="text-sm" style="width: 10cm">
    <tbody>
        <tr>
            <td><strong>Nama Freelance</strong></td>
            <td>:</td>
            <td>{{ $invoice->order->salesperson->name }}</td>
        </tr>

        <tr>
            <td><strong>Area Pemasaran</strong></td>
            <td>:</td>
            <td>
                @foreach ($invoice->order->salesperson->area_pemasarans as $area)
                    {{ $area->name }};
                @endforeach
            </td>
        </tr>

        {{-- <tr>
            <td><strong>{{ $invoice->type === 'Masuk' ? 'Dari' : 'Kepada' }}</strong></td>
            <td>:</td>
            <td style="border-bottom: 1px dotted #000"></td>
        </tr> --}}

    </tbody>
</table>
@stop

@section('content')
<table cellspacing="0" cellpadding="0" class="table table-sm table-bordered" style="width: 100%">
    <thead>
        <th width="1%" class="text-center">No.</th>
        <th>Jenjang - Kelas</th>
        <th>Tema/Mapel</th>
        <th width="1%" class="text-center">Hal</th>
        <th width="15%" class="text-right">Harga</th>
        <th width="1%" class="text-center">Qty</th>
        <th width="20%" class="text-right">Subtotal</th>
    </thead>

    <tbody>
        @foreach ($buku as $item)
            @php
            $product = $item->product;
            @endphp
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $product->jenjang->name ?? '' }} - Kelas {{ $product->kelas->name ?? '' }}</td>
                <td>{{ $product->name }}</td>
                <td class="text-center">{{ $product->halaman->name ?? '' }}</td>
                <td class="text-right">@money($item->price)</td>
                <td class="text-center">{{ abs($item->quantity) }}</td>
                <td class="text-right">@money(abs($item->total))</td>
            </tr>
        @endforeach
    </tbody>

    <thead>
        <th colspan="7">Kelengkapan</th>
    </thead>

    <tbody>
        @foreach ($kelengkapan as $item)
            @php
            $product = $item->product;
            @endphp
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $product->jenjang->name ?? '' }} - Kelas {{ $product->kelas->name ?? '' }}</td>
                <td>{{ $product->name }}</td>
                <td class="text-center">{{ $product->halaman->name ?? '' }}</td>
                <td class="text-center">-</td>
                <td class="text-center">{{ abs($item->quantity) }}</td>
                <td class="text-right">@money(abs($item->total))</td>
            </tr>
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <td colspan="6" class="text-center px-3"><strong>Total</strong></td>
            <td class="text-right">@money(abs($invoice->nominal))</td>
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
    size: portrait;
}
</style>
@endpush
