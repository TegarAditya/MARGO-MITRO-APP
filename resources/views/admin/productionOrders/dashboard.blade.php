@extends('layouts.admin')
@section('content')
<div class="container container-chart">
    <form action="{{ route('admin.home') }}" method="POST">
        @csrf

        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">Dashboard Admin</h3>
            </div>
        </div>

        <div class="row mt-4">
            @foreach ([
                [
                    'icon' => 'fas fa-hand-holding-usd h1',
                    'label' => 'Order',
                    'content' => $orders->count(),
                ], [
                    'icon' => 'fas fa-box h1',
                    'label' => 'Produk Dipesan',
                    'content' => $orders->map(function($item) {
                        return $item->order_details->sum('quantity');
                    })->sum(),
                ], [
                    'icon' => 'fas fa-file-invoice-dollar h1',
                    'label' => 'Surat Jalan',
                    'content' => $orders->map(function($item) {
                        return $item->invoices->count();
                    })->sum(),
                ], [
                    'icon' => 'fa fa-truck h1',
                    'label' => 'Produk Keluar',
                    'content' => $orders->map(function($item) {
                        return $item->order_details->sum('moved');
                    })->sum(),
                ]
            ] as $card)
                <div class="col-3 d-flex flex-column">
                    <div class="card mb-0 rounded-xl flex-grow-1">
                        <div class="card-body">
                            <i class="{{ $card['icon'] }}"></i>

                            <h3 class="font-weight-bold mb-1">{{ $card['content'] }}</h3>

                            <p class="m-0">{{ $card['label'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row mt-4">
            <div class="col-12 d-flex flex-column">
                @foreach ([
                    [
                        'icon' => 'fas fa-money-bill h3',
                        'label' => 'Total Order',
                        'content' => money($orders->sum('tagihan.total')),
                    ], [
                        'icon' => 'fas fa-dollar-sign h3',
                        'label' => 'Total Tagihan',
                        'content' => money($orders->map(function($item) {
                            return $item->invoices->sum('nominal');
                        })->sum()),
                    ], [
                        'icon' => 'fa fa-money-check-alt h3',
                        'label' => 'Total Pembayaran',
                        'content' => money($orders->map(function($item) {
                            return $item->pembayarans->sum('nominal');
                        })->sum()),
                    ], [
                        'icon' => 'fa fa-wallet h3',
                        'label' => 'Sisa Tagihan',
                        'content' => money($orders->sum('sisa_tagihan')),
                    ],
                ] as $card)
                    <div class="card {{ !$loop->last ? 'mb-3' : 'mb-0' }} rounded-xl flex-grow-1">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto pr-2 text-center" style="min-width:2.5rem">
                                    <i class="{{ $card['icon'] }}"></i>
                                </div>

                                <div class="col">
                                    <h6 class="mb-2">{{ $card['label'] }}</h6>

                                    <h4 class="m-0">{{ $card['content'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
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
