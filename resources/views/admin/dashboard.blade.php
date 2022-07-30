@extends('layouts.admin')
@section('content')
<div class="container container-chart">
    <form action="{{ route('admin.home') }}" method="POST">
        @csrf

        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">Dashboard Admin</h3>
            </div>

            <div class="col-auto">
                <x-admin.form-group
                    type="text"
                    id="date"
                    name="date"
                    containerClass=" m-0"
                    boxClass=" px-2 py-1"
                    class="form-control-sm product-price"
                    value="{{ request('date', old('date'))}}"
                    placeholder="Pilih Tanggal"
                >
                    <x-slot name="right">
                        <button type="button" class="btn btn-sm border-0 btn-default px-2 date-clear" data-action="+" style="display:{{ !request('date', old('date')) ? 'none' : 'block' }}">
                            <i class="fa fa-times"></i>
                        </button>
                    </x-slot>
                </x-admin.form-group>
            </div>

            <div class="col-auto">
                <div class="form-group mb-0">
                    <select class="form-control select2 {{ $errors->has('salesperson') ? 'is-invalid' : '' }}" name="salesperson_id" id="salesperson_id">
                        @foreach($salespeople as $id => $entry)
                            <option value="{{ $id }}" {{ (old('salesperson_id') ? old('salesperson_id') : $order->salesperson->id ?? request('salesperson_id')) == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('salesperson'))
                        <span class="text-danger">{{ $errors->first('salesperson') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.order.fields.salesperson_helper') }}</span>
                </div>
            </div>

            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filter</button>
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
            <div class="col-4 d-flex flex-column">
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

            <div class="col-8 d-flex flex-column">
                <div class="card mb-0 rounded-xl flex-grow-1">
                    <div class="card-body text-secondary">
                        <div class="row">
                            <div class="col">
                                <h5 class="m-0">Grafik Transaksi</h5>
                            </div>

                            <div class="col-auto">
                                <p class="m-0">
                                    {{ $start_at->translatedFormat('j M Y')}} - {{ $end_at->translatedFormat('j M Y')}}
                                </p>
                            </div>
                        </div>

                        <div class="dashboard-chart mt-3">
                            <canvas id="dashboardChart" height="150"></canvas>
                        </div>
                    </div>
                </div>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.0/dist/index.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.2/dist/chart.min.js"></script>

<script>
(function($) {
    $(function() {
        var picker = new easepick.create({
            element: $('#date').get(0),
            css: [
                'https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.0/dist/index.css',
            ],
            plugins: ['RangePlugin', 'LockPlugin'],
            RangePlugin: {
                tooltip: true,
            },
            LockPlugin: {
                maxDate: new Date(),
            },
        });

        picker.on('select', function(e) {
            $('#date').trigger('change');
            $('.date-clear').show();
        });

        $('.date-clear').on('click', function(e) {
            e.preventDefault();

            picker.clear();
            $(e.currentTarget).hide();
        });
    });
})(jQuery);
</script>

@php
// Total Order
$total_order = $orders->map(function($order) {
    return [ 'x' => $order->date, 'y' => data_get($order, 'tagihan.total', 0)];
});

// Total Tagihan
$total_tagihan = $orders->map(function($order) {
    return [ 'x' => $order->date, 'y' => $order->invoices->sum('nominal')];
});

// Total Pembayaran
$total_pembayaran = $orders->map(function($order) {
    return [ 'x' => $order->date, 'y' => $order->pembayarans->sum('nominal')];
});

// Sisa tagihan
$sisa_tagihan = $orders->map(function($order) {
    return [ 'x' => $order->date, 'y' => $order->sisa_tagihan ?: 0];
});

if (!$orders->where('date', $start_at->format('Y-m-d'))->count()) {
    $total_order->prepend([ 'x' => $start_at->format('Y-m-d'), 'y' => 0 ]);
    $total_tagihan->prepend([ 'x' => $start_at->format('Y-m-d'), 'y' => 0 ]);
    $total_pembayaran->prepend([ 'x' => $start_at->format('Y-m-d'), 'y' => 0 ]);
    $sisa_tagihan->prepend([ 'x' => $start_at->format('Y-m-d'), 'y' => 0 ]);
}

if (!$orders->where('date', $end_at->format('Y-m-d'))->count()) {
    $total_order->push([ 'x' => $end_at->format('Y-m-d'), 'y' => 0 ]);
    $total_tagihan->push([ 'x' => $end_at->format('Y-m-d'), 'y' => 0 ]);
    $total_pembayaran->push([ 'x' => $end_at->format('Y-m-d'), 'y' => 0 ]);
    $sisa_tagihan->push([ 'x' => $end_at->format('Y-m-d'), 'y' => 0 ]);
}
@endphp

<script>
(function($) {
    const ctx = $('#dashboardChart').get(0).getContext('2d');

    const dashboardChart = new Chart(ctx, {
        type: 'line',
        data: {
            datasets: [{
                label: 'Total Order',
                data: {!! $total_order->toJSON() !!},
                backgroundColor: 'gold',
                borderColor: 'gold',
            }, {
                label: 'Total Tagihan',
                data: {!! $total_tagihan->toJSON() !!},
                backgroundColor: 'lime',
                borderColor: 'lime',
            }, {
                label: 'Total Pembayaran',
                data: {!! $total_pembayaran->toJSON() !!},
                backgroundColor: 'turquoise',
                borderColor: 'turquoise',
            }, {
                label: 'Sisa Tagihan',
                data: {!! $sisa_tagihan->toJSON() !!},
                backgroundColor: 'brown',
                borderColor: 'brown',
            }]
        },
        options: {
            datasets: {
                line: {
                    tension: 0.2,
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: function(value, index) {
                            return ((index + 1) % 2 === 0) ? '' : numeral(value).format('0a');
                        },
                    },
                    grid: { color: '#eee' },
                },
                x: {
                    grid: { display: false },
                }
            }
        }
    });
})(jQuery);
</script>
@endpush
