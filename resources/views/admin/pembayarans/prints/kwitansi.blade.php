@extends('layouts.print')

@php
function penyebut($nilai) {
    $nilai = abs($nilai);
    $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($nilai < 12) {
        $temp = " ". $huruf[$nilai];
    } else if ($nilai <20) {
        $temp = penyebut($nilai - 10). " belas";
    } else if ($nilai < 100) {
        $temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
    } else if ($nilai < 200) {
        $temp = " seratus" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
        $temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
    } else if ($nilai < 2000) {
        $temp = " seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
        $temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
        $temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
        $temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
    } else if ($nilai < 1000000000000000) {
        $temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
    }     
    return $temp;
}

function terbilang($nilai) {
    if($nilai<0) {
        $hasil = "minus ". trim(penyebut($nilai));
    } else {
        $hasil = trim(penyebut($nilai));
    }     		
    return $hasil;
}
@endphp

@section('header.right')
<h5>KWITANSI</h5>

<table cellspacing="0" cellpadding="0" class="text-sm" style="width: 9cm">
    <tbody>
        <tr>
            <td width="120"><strong>No. Kwitansi</strong></td>
            <td width="8">:</td>
            <td>{{ $pembayaran->no_kwitansi }}</td>
        </tr>

        <tr>
            <td><strong>Tanggal</strong></td>
            <td>:</td>
            <td>{{ $pembayaran->tanggal }}</td>
        </tr>
    </tbody>
</table>
@stop

@section('content')
<div class="row">
    <div class="col-auto pl-1">
        <table cellpadding="0" cellspacing="0" class="table table-sm table-borderless mb-0">
            <tbody>
                <tr>
                    <td width="180">Telah diterima dari</td>
                    <td width="12">:</td>
                    <td style="border-bottom: 1px dotted #000"></td>
                </tr>

                <tr>
                    <td width="120">Sejumlah uang</td>
                    <td width="8">:</td>
                    <td class="px-0">@money($pembayaran->bayar)</td>
                </tr>

                @if ($pembayaran->diskon)
                    <tr>
                        <td width="120">Dari tagihan</td>
                        <td width="8">:</td>
                        <td class="px-0">@money($pembayaran->nominal) <em>(Diskon <del>@money($pembayaran->diskon)</del>)</em></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="col ml-5 align-self-end">
        <p class="mb-2 font-weight-bold small">Terbilang</p>

        <div class="d-flex align-items-center" style="border-radius:.5rem;border: 1px dashed #000;padding: .5rem 1rem; min-height: 3em">
            <p class="m-0 text-uppercase">{{ terbilang($pembayaran->bayar) }}</p>
        </div>

        @if ($pembayaran->note)
            <p class="mt-2 mb-0 small">
                <em>Catatan: {{ $pembayaran->note }}</em>
            </p>
        @endif
    </div>
</div>
@endsection

@section('footer')
<h6>Dari Tagihan</h6>

<div class="row">
    <div class="col align-self-end">
        @if ($tagihan = $pembayaran->tagihan)
            <table cellpadding="0" cellspacing="0" style="width: 360px">
                <tbody>
                    <tr>
                        <td width="150">Total Tagihan</td>
                        <td width="12">:</td>
                        <td>@money($tagihan->total)</td>
                    </tr>
            
                    <tr>
                        <td width="120">Total Pembayaran</td>
                        <td width="8">:</td>
                        <td>@money($tagihan->selisih)</td>
                    </tr>

                    <tr>
                        <td width="120">Sisa Tagihan</td>
                        <td width="8">:</td>
                        <td>@money($tagihan->saldo)</td>
                    </tr>

                    <tr class="mt-3">
                        <td class="pt-2" width="120">Status</td>
                        <td class="pt-2" width="8">:</td>
                        <td class="pt-2">{{ !$tagihan->saldo ? 'Lunas' : 'Belum Lunas' }}</td>
                    </tr>
                </tbody>
            </table>
        @endif
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
