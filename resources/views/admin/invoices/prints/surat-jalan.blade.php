@extends('layouts.print')

@section('header.center')
<h6>SURAT JALAN</h6>
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
            <td>{{ $invoice->date }}</td>
        </tr>

        {{-- <tr>
            <td><strong>{{ $invoice->type === 'Masuk' ? 'Dari' : 'Kepada' }}</strong></td>
            <td>:</td>
            <td style="border-bottom: 1px dotted #000"></td>
        </tr> --}}
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
            <td><strong>Alamat</strong></td>
            <td>:</td>
            <td></td>
        </tr> --}}
    </tbody>
</table>
@endsection

@section('content')
<table cellspacing="0" cellpadding="0" class="table table-sm table-bordered" style="width: 100%">
    <thead>
        <th width="1%" class="text-center">No.</th>
        <th>Jenjang - Kelas</th>
        <th>Tema/Mapel</th>
        <th width="1%" class="text-center">Hal</th>
        <th class="px-3" width="1%">Jumlah</th>
        <th class="px-3" width="1%">Kelengkapan</th>
    </thead>

    <tbody>
        @foreach ($inv_details as $detail)
            @php
            $product = $detail->product;
            $bonus = $detail->bonus ?? null;
            if ($bonus) {
                $product_bonus = $bonus->product;
                $qty_bonus = $bonus->quantity;
            }
            @endphp
            <tr>
                <td class="px-3">{{ $loop->iteration }}</td>
                <td>{{ $product->jenjang->name ?? '' }} - Kelas {{ $product->kelas->name ?? '' }}</td>
                <td>{{ $product->name }}</td>
                <td class="text-center">{{ $product->halaman->name ?? '' }}</td>
                <td class="px-3 text-center">{{ abs($detail->quantity) }}</td>
                <td class="px-3 text-center">{{ $bonus ? abs($qty_bonus) : '-' }}</td>
            </tr>
        @endforeach

        @foreach ($pg_details as $detail)
            @php
            $product = $detail->product;
            @endphp
            <tr>
                <td class="px-3">{{ $loop->iteration }}</td>
                <td>{{ $product->jenjang->name ?? '' }} - Kelas {{ $product->kelas->name ?? '' }}</td>
                <td>{{ $product->name }}</td>
                <td class="text-center">{{ $product->halaman->name ?? '' }}</td>
                <td class="px-3 text-center">{{ abs($detail->quantity) }}</td>
                <td class="px-3 text-center">-</td>
            </tr>
        @endforeach
    </tbody>
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
