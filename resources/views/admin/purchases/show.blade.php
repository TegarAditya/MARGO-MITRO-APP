@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Show Produk Masuk
    </div>

    <div class="card-body">
        <div class="row">

            <div class="col">
                <a class="btn btn-default" href="{{ url()->previous() }}">
                    Back
                </a>
            </div>

            <div class="col-auto">
                <a class="btn btn-info" href="{{ route('admin.purchases.edit', $purchase->id) }}">
                    Edit Produk Masuk
                </a>
            </div>
        </div>

        <div class="model-detail mt-3">

            <section class="py-3" id="modelDetail">
                <h6>Detail Produk Masuk</h6>

                <table class="table table-sm border m-0">
                    <tbody>
                        <tr>
                            <th width="200">
                                No Produk Masuk
                            </th>
                            <td>
                                {{ $purchase->no_spk }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Tanggal
                            </th>
                            <td>
                                {{ $purchase->date }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Subkontraktor
                            </th>
                            <td>
                                {{ $purchase->subkontraktor ? $purchase->subkontraktor->name : '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Semester
                            </th>
                            <td>
                                {{ $purchase->semester ? $purchase->semester->name : '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Tanggal
                            </th>
                            <td>
                                {{ $purchase->date }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Catatan
                            </th>
                            <td>
                                {{ $purchase->note ?? '' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <section class="border-top py-3">
                <div class="row mb-2">
                    <div class="col">
                        <h6>Daftar Produk</h6>

                        <p class="mb-0">Total Produk : <strong>{{ $purchase->details->count() }}</strong></p>
                        <p class="mb-0">Total Eksemplar : <strong>{{ $purchase->details->sum('quantity') }}</strong></p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body px-3 py-2">
                        <table class="table table-sm table-bordered m-0">
                            <thead>
                                <tr>
                                    <th class="text-center" width="1%">No.</th>
                                    <th>Nama Produk</th>
                                    <th class="text-center px-3" width="1%">Quantity</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($purchase->details as $detail)
                                    @php
                                    $product = $detail->product;
                                    @endphp
                                    <tr>
                                        <td class="text-right px-3">{{ $loop->iteration }}.</td>
                                        <td>{{ $product->nama_isi_buku }}</td>
                                        <td class="text-center px-3">{{ abs($detail->quantity) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
