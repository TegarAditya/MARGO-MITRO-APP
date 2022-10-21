@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-body">
        <div class="model-detail">
            <h5>Dashboard PO</h5>

            <p>Daftar Order untuk dilanjutkan menuju Production Order</p>

            {{-- List Order --}}
            <section class="border-top py-3" id="modelProduct">
                <h5 class="mb-3">Daftar Sales Order</h5>

                @foreach ([1, 2, 3, 4] as $item)
                    <div class="card mb-2">
                        <div class="card-body px-3 py-2">
                            <div class="row">
                                <div class="col">
                                    <h6 class="mb-0">
                                        SO ORD/GENAP/MMJ/X/22/011
                                    </h6>
                                </div>

                                <div class="col-auto">
                                    <a href="#" class="btn btn-primary btn-sm">
                                        Buat PO
                                    </a>
                                </div>
                            </div>

                            <div class="row align-items-end align-self-center mt-3">
                                <div class="col" style="margin-left: 5rem">
                                    @foreach ([1, 2, 3, 4] as $product)
                                        <h6 class="text-sm product-name mb-0">Product {{ $loop->iteration }}</h6>

                                        <p class="mb-2 text-sm">
                                            Jenjang: SMP
                                        </p>
            
                                        <div class="row">
                                            <div class="col-4">
                                                <p class="mb-0 text-sm">
                                                    Stock: 0
                                                </p>
            
                                                <p class="mb-0 text-sm">
                                                    HPP: Rp50.000
                                                </p>
                                            </div>
            
                                            <div class="col row align-items-end align-self-center">
                                                <div class="col" style="max-width: 120px">
                                                    <p class="mb-0 text-sm">
                                                        Pesanan: 10000
                                                    </p>
            
                                                    <p class="mb-0 text-sm">
                                                        Terkirim: 0
                                                    </p>
                                                </div>
            
                                                <div class="col text-right" style="max-width: 240px">
                                                    <p class="text-sm mb-0">Harga</p>
                                                    <p class="m-0">Rp100.0000</p>
                                                </div>
            
                                                <div class="col text-right">
                                                    <p class="text-sm mb-0">Subtotal</p>
                                                    <p class="m-0">Rp100.000.000</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="col-auto">
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
</style>
@endpush
