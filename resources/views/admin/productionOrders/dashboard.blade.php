@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-body">
        <div class="model-detail">
            <h5>Dashboard PO</h5>

            <p>Daftar Order untuk dilanjutkan menuju Production Order</p>

            {{-- List Order --}}
            <section class="border-top py-3" id="modelProduct">
                <h6 class="mb-3">Daftar Sales Order</h6>

                @foreach ([1, 2, 3, 4] as $item)
                    <div class="card mb-2">
                        <div class="card-body px-3 py-2">
                            <h6 class="text-sm product-name mb-0">
                                Produk #{{ $loop->iteration }}
                            </h6>

                            <div class="row align-items-end align-self-center">
                                <div class="col" style="max-width: 120px">
                                    <p class="mb-0 text-sm">
                                        Pesanan: 1
                                    </p>

                                    <p class="mb-0 text-sm">
                                        Terkirim: 2
                                    </p>
                                </div>

                                <div class="col text-right" style="max-width: 240px">
                                    <p class="text-sm mb-0">Harga</p>
                                    <p class="m-0">@money(123456789)</p>
                                </div>

                                <div class="col text-right">
                                    <p class="text-sm mb-0">Subtotal</p>
                                    <p class="m-0">@money(123456789)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </section>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.container-chart {
    color: #1b1853
}

.card {
    transition: 250ms ease-in-out box-shadow;
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.025),
        0 .25rem .5rem rgba(0,0,0,0.025),
        0 .5rem .5rem rgba(0,0,0,.025);
}
.card:hover {
    cursor: pointer;
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.025),
        0 .25rem .5rem rgba(15,15,65,0.0375),
        0 .5rem .5rem rgba(25,25,155,.05);
}
</style>
@endpush
