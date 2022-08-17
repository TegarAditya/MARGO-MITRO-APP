@extends('layouts.print')

@section('header.right')
<h6>Saldo Order</h6>

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
@foreach ($invoices as $invoice)
    <div class="row">
        <div class="col-3">
            <p class="mb-0 text-sm">
                No. Invoice
                <br />
                <strong>{{ $invoice->no_invoice }}</strong>
            </p>
        </div>
        <div class="col-3">
            <p class="mb-0 text-sm">
                Tanggal
                <br />
                <strong>{{ Carbon\Carbon::parse($invoice->date)->format('d-m-Y') }}</strong>
            </p>
        </div>
    </div>
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
            @foreach ($invoice->invoice_details as $invoice_detail)
                @php
                $product = $invoice_detail->product;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $product->jenjang->name ?? '' }} - Kelas {{ $product->kelas->name ?? '' }}</td>
                    <td>{{ $product->name }}</td>
                    <td class="text-center">{{ $product->halaman->name ?? '' }}</td>
                    <td class="text-right">@money($invoice_detail->price)</td>
                    <td class="text-center">{{ abs($invoice_detail->quantity) }}</td>
                    <td class="text-right">@money(abs($invoice_detail->total))</td>
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
@endforeach
<div class="my-2 mb-2 ml-5 text-right">
    <p class="m-0">Total Tagihan</p>
    <h5 class="m-0">@money($invoices->sum('nominal'))</h5>
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
            <span class="h5 mb-0 tagihan-total font-weight-bold">@money($invoices->sum('nominal'))</span>
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
    size: landscape;
}
</style>
@endpush
