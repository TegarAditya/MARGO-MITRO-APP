@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} Faktur
    </div>

    <div class="card-body">
        <div class="form-group">
            <a class="btn btn-default" href="{{ url()->previous() }}">
                Back
            </a>
        </div>
        <div class="model-detail mt-`3">
            <section class="py-3">
                @php
                $print = function($type) use ($invoice) {
                    return route('admin.faktur.show', ['faktur' => $invoice->id, 'print' => $type]);
                };
                @endphp
                <div class="card">
                    <div class="card-body px-3 py-2">
                        <div class="row">
                            <div class="col-6 mb-1">
                                <span class="badge badge-warning">Surat Jalan</span>
                            </div>

                            <div class="col-6 text-right">
                                <a href="{{ route('admin.invoices.edit', $invoice->id) }}" class="border-bottom">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                            </div>

                            <div class="col-3">
                                <p class="mb-0 text-sm">
                                    No. Invoice
                                    <br />
                                    <strong>{{ $invoice->no_invoice }}</strong>
                                </p>
                            </div>

                            <div class="col-3">
                                <p class="mb-0 text-sm">
                                    No. Surat Jalan
                                    <br />
                                    <strong>{{ $invoice->no_suratjalan }}</strong>

                                    <a href="{{ $print('sj') }}" class="fa fa-lg fa-print ml-1 text-info" title="Print Surat Jalan" target="_blank"></a>
                                </p>
                            </div>

                            <div class="col text-right">
                                <span>Tanggal<br />{{ $invoice->date }}</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-3">
                                <p class="mb-0 text-sm">
                                    Salesman
                                    <br />
                                    <strong>{{ $invoice->order->salesperson->name }}</strong>
                                </p>
                            </div>

                            <div class="col-3">
                                <p class="mb-0 text-sm">
                                    Semester
                                    <br />
                                    <strong>{{ $invoice->order->semester->name }}</strong>
                                </p>
                            </div>
                        </div>
                        <br>
                        <p class="mt-3 mb-3">
                            <strong>Daftar Produk</strong>
                        </p>

                        <table class="table table-sm table-bordered m-0">
                            <thead>
                                <tr>
                                    <th class="text-center" width="1%">No.</th>
                                    <th>Jenjang</th>
                                    <th>Tema/Mapel</th>
                                    {{-- <th class="text-center px-3" width="15%">Harga</th> --}}
                                    <th class="text-center px-3" width="10%">Quantity</th>
                                    {{-- <th class="text-center px-3" width="20%">Subtotal</th> --}}
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($invoice->invoice_details as $invoice_detail)
                                    @php
                                    $product = $invoice_detail->product;
                                    @endphp
                                    <tr>
                                        <td class="text-right px-3">{{ $loop->iteration }}.</td>
                                        <td class="text-center">{{ $product->jenjang->name ?? '' }}</td>
                                        <td>{{ $product->nama_isi_buku }}</td>
                                        {{-- <td class="text-right px-3">@money(abs($invoice_detail->price))</td> --}}
                                        <td class="text-center px-3">{{ angka(abs($invoice_detail->quantity)) }}</td>
                                        {{-- <td class="text-right px-3">@money(abs($invoice_detail->total))</td> --}}
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="px-3" colspan="6">Tidak ada produk</td>
                                    </tr>
                                @endforelse
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td class="text-center px-3" colspan="3"><strong>Total</strong></td>
                                    <td class="text-center px-3"><strong>{{ angka(abs($invoice->invoice_details->sum('quantity'))) }}</strong></td>
                                    {{-- <td class="text-right px-3">
                                        <strong>@money(abs($invoice->nominal))</strong>
                                    </td> --}}
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
