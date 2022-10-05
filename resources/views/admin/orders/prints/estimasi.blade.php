@extends('layouts.print')

@section('header.right')
<h6>Estimasi Order</h6>

<table cellspacing="0" cellpadding="0" class="text-sm" style="width: 10cm">
    <tbody>
        <tr>
            <td width="136"><strong>No. Order</strong></td>
            <td width="12">:</td>
            <td>{{ $order->no_order }}</td>
        </tr>

        {{-- <tr>
            <td width="120"><strong>No. Surat Jalan</strong></td>
            <td width="8">:</td>
            <td>{{ $invoice->no_suratjalan }}</td>
        </tr> --}}

        <tr>
            <td><strong>Tanggal</strong></td>
            <td>:</td>
            <td>{{ Carbon\Carbon::parse($order->date)->format('d-m-Y') }}</td>
        </tr>

        <tr>
            <td><strong>Nama Freelance</strong></td>
            <td>:</td>
            <td>{{ $order->salesperson->name }}</td>
        </tr>

        <tr>
            <td><strong>Area Pemasaran</strong></td>
            <td>:</td>
            <td>
                @foreach ($order->salesperson->area_pemasarans as $area)
                    {{ $area->name }};
                @endforeach
            </td>
        </tr>

    </tbody>
</table>
@stop

@section('content')
<table cellspacing="0" cellpadding="0" class="table table-sm table-bordered" style="width: 100%">
    <thead>
        <tr>
            <th rowspan="2" width="1%" class="align-middle text-center">No.</th>
            <th rowspan="2" class="align-middle">Jenjang</th>
            <th rowspan="2" class="align-middle">Kelas</th>
            <th rowspan="2" class="align-middle">Tema/Mapel</th>
            <th rowspan="2" width="1%" class="align-middle text-center">Hal</th>
            <th colspan="2" class="text-center">Pesanan</th>
            <th colspan="2" class="text-center">Dikirim</th>
            <th colspan="2" class="text-center">Sisa</th>
        </tr>
        <tr>
            <th class="text-center">Buku</th>
            <th class="text-center">PG</th>
            <th class="text-center">Buku</th>
            <th class="text-center">PG</th>
            <th class="text-center">Buku</th>
            <th class="text-center">PG</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($details as $detail)
            @php
            $product = $detail->product;
            $bonus = $detail->bonus;
            @endphp
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $product->jenjang->name ?? '' }}</td>
                <td>Kelas {{ $product->kelas->name ?? '' }}</td>
                <td>{{ $product->name }}</td>
                <td class="text-center">{{ $product->halaman->name ?? '' }}</td>
                <td class="text-center">{{ $detail->quantity }}</td>
                <td class="text-center">{{ $bonus ? $bonus->quantity : '-' }}</td>
                <td class="text-center">{{ $detail->moved }}</td>
                <td class="text-center">{{ $bonus ? $bonus->moved : '-' }}</td>
                <td class="text-center">{{ ($detail->quantity - $detail->moved) }}</td>
                <td class="text-center">{{ $bonus ? ($bonus->quantity - $bonus->moved) : '-' }}</td>
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

    {{-- <div class="col-auto text-center">
        <p class="mb-5">Pengirim</p>
        <p class="mb-0">( _____________ )</p>
    </div>

    <div class="col-auto text-center">
        <p class="mb-5">Penerima</p>
        <p class="mb-0">( _____________ )</p>
    </div> --}}
</div>
@endsection

@push('styles')
<style type="text/css" media="print">
@page {
    size: landscape;
}
</style>
@endpush
