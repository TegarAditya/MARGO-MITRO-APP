@extends('layouts.print')

@section('header.center')
<h6>REKAP SALDO ORDER</h6>
@stop

@section('header.left')
<table cellspacing="0" cellpadding="0" class="text-sm" style="width: 10cm">
    <tbody>
        <tr>
            <td width="136"><strong>No. Order</strong></td>
            <td width="12">:</td>
            <td>{{ $order->no_order }}</td>
        </tr>

        <tr>
            <td><strong>Tanggal</strong></td>
            <td>:</td>
            <td>{{ Carbon\Carbon::parse($order->date)->format('d-m-Y') }}</td>
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
<h5>Pengiriman</h5>
<table cellspacing="0" cellpadding="0" class="table table-sm table-bordered mt-2" style="width: 100%">
    <thead>
        <th width="1%" class="text-center">No.</th>
        <th class="text-center">No. Invoice</th>
        <th class="text-center">No. Surat Jalan</th>
        <th class="text-center">Tanggal</th>
        <th class="text-center">Total Eksemplar</th>
        <th width="20%" class="text-center">Total Kirim</th>
    </thead>

    <tbody>
        @php
            $total_eksemplar = 0
        @endphp
        @forelse ($kirims as $invoice)
            @php
                $total_eksemplar += abs($invoice->invoice_details->sum('quantity'));
            @endphp
            <tr>
                <td class="text-right px-3">{{ $loop->iteration }}.</td>
                <td>{{ $invoice->no_suratjalan }}</td>
                <td>{{ $invoice->no_invoice }}</td>
                <td>{{ $invoice->date }}</td>
                <td class="text-center">{{ angka(abs($invoice->invoice_details->sum('quantity')))}}</td>
                <td class="text-right px-3">@money($invoice->nominal)</td>
            </tr>
        @empty
            <tr>
                <td class="px-3" colspan="6">Belum ada pemngiriman</td>
            </tr>
        @endforelse
    </tbody>

    <tfoot>
        <tr>
            <td colspan="4" class="text-center px-3"><strong>Total</strong></td>
            <td class="text-center">{{ angka($total_eksemplar) }}</td>
            <td class="text-right">@money(abs($kirims->sum('nominal')))</td>
        </tr>
    </tfoot>
</table>

@if ($returs->count() > 0)
<h5>Retur</h5>
<table cellspacing="0" cellpadding="0" class="table table-sm table-bordered mt-2" style="width: 100%">
    <thead>
        <th width="1%" class="text-center">No.</th>
        <th>No. Invoice</th>
        <th>No. Surat Jalan</th>
        <th>Tanggal</th>
        <th width="20%" class="text-right">Total Kirim</th>
    </thead>

    <tbody>
        @forelse ($returs as $invoice)
            <tr>
                <td class="text-right px-3">{{ $loop->iteration }}.</td>
                <td>{{ $invoice->no_suratjalan }}</td>
                <td>{{ $invoice->no_invoice }}</td>
                <td>{{ $invoice->date }}</td>
                <td class="text-right px-3">@money(abs($invoice->nominal))</td>
            </tr>
        @empty
            <tr>
                <td class="px-3" colspan="5">Belum ada Retur</td>
            </tr>
        @endforelse
    </tbody>

    <tfoot>
        <tr>
            <td colspan="4" class="text-center px-3"><strong>Total</strong></td>
            <td class="text-right">@money(abs($returs->sum('nominal')))</td>
        </tr>
    </tfoot>
</table>
@endif

<div class="my-2 mb-2 ml-5 text-right">
    <p class="m-0">Total Tagihan</p>
    <h5 class="m-0">@money($total_invoice)</h5>
</div>
<hr class="my-2 mt-4 text-right mx-0" />
<h5>Pembayaran</h5>
<table cellspacing="0" cellpadding="0" class="table table-sm table-bordered mt-2" style="width: 100%">
    <thead>
        <th width="1%" class="text-center">No.</th>
        <th>No. Kwitansi</th>
        <th>Tanggal</th>
        <th width="20%" class="text-right">Bayar</th>
        <th width="15%" class="text-right">Bonus</th>
        <th width="20%" class="text-right">Subtotal</th>
    </thead>

    <tbody>
        @forelse ($pembayarans as $pembayaran)
            <tr>
                <td class="text-right px-3">{{ $loop->iteration }}.</td>
                <td>{{ $pembayaran->no_kwitansi }}</td>
                <td>{{ $pembayaran->tanggal }}</td>
                <td class="text-right px-3">@money($pembayaran->bayar)</td>
                <td class="text-right px-3">
                    @if (!$pembayaran->diskon)
                        <span>-</span>
                    @else
                        <span>@money($pembayaran->diskon)</span>
                    @endif
                </td>
                <td class="text-right px-3">@money($pembayaran->nominal)</td>
            </tr>
        @empty
            <tr>
                <td class="px-3" colspan="6">Belum ada pembayaran</td>
            </tr>
        @endforelse
    </tbody>

    <tfoot>
        <tr>
            <td colspan="5" class="text-center px-3"><strong>Total</strong></td>
            <td class="text-right">@money(abs($pembayarans->sum('nominal')))</td>
        </tr>
    </tfoot>
</table>
<hr class="my-2 text-right mx-0" />

<div class="row text-right">
    <div class="col text-left">
        <h5 class="m-0">Detail Tagihan</h5>
    </div>

    <div class="col-auto">
        <p class="mb-0">
            <span>Total Tagihan</span>
            <br />
            <span class="h5 mb-0 tagihan-total font-weight-bold">@money($total_invoice)</span>
        </p>
    </div>

    <div class="col-auto">
        <p class="mb-0">
            <span>Total Pembayaran</span>
            <br />
            <span class="h5 mb-0 tagihan-total font-weight-bold">@money($pembayarans->sum('nominal'))</span>
        </p>
    </div>

    <div class="col-auto">
        <p class="mb-0">
            <span>Sisa Hutang</span>
            <br />
            <span class="h5 mb-0 tagihan-total font-weight-bold">@money($order->sisa_tagihan)</span>
        </p>
    </div>
</div>
<div class="mt-5"></div>
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
