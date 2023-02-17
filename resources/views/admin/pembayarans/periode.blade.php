@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Laporan Pembayaran
    </div>

    <div class="card-body">
        <form action="{{ route('admin.pembayarans.periode') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-4">
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
                        <x-slot name="label">
                            <label class="small mb-0" for="date">Tanggal</label>
                        </x-slot>

                        <x-slot name="right">
                            <button type="button" class="btn btn-sm border-0 btn-default px-2 date-clear" data-action="+" style="display:{{ !request('date', old('date')) ? 'none' : 'block' }}">
                                <i class="fa fa-times"></i>
                            </button>
                        </x-slot>
                    </x-admin.form-group>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-4">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
        <div class="table-responsive mt-5">
            @php
                $totalpesanan = 0;
                $totalretur = 0;
                $totalbayar = 0;
                $totaldiskon = 0;
            @endphp
            <table class="table table-bordered table-striped table-hover datatable-saldo">
                <thead>
                    <tr>
                        <th></th>
                        <th>Sales</th>
                        <th>Tagihan</th>
                        <th>Retur</th>
                        <th>Pembayaran</th>
                        <th>Diskon</th>
                        <th>Hutang</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($saldos as $saldo)
                    @php
                        $pesanan = $saldo->invoices->where('nominal', '>', 0)->sum('nominal');
                        $retur = abs($saldo->invoices->where('nominal', '<', 0)->sum('nominal'));
                        $bayar = $saldo->pembayarans->sum('nominal');
                        $diskon = $saldo->pembayarans->sum('diskon');
                        $totalpesanan += $pesanan;
                        $totalretur += $retur;
                        $totalbayar += $bayar;
                        $totaldiskon += $diskon;
                    @endphp
                    <tr>
                        <td></td>
                        <td>{{ $saldo->name }}</td>
                        <td class="text-right">@money($pesanan)</td>
                        <td class="text-right">@money($retur)</td>
                        <td class="text-right">@money($bayar)</td>
                        <td class="text-right">@money($diskon)</td>
                        <td class="text-right">@money($pesanan - $bayar)</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-center">
                            <strong>Total</strong>
                        </td>
                        <td class="text-right">@money($totalpesanan)</td>
                        <td class="text-right">@money($totalretur)</td>
                        <td class="text-right">@money($totalbayar)</td>
                        <td class="text-right">@money($totaldiskon)</td>
                        <td class="text-right">@money($totalpesanan - $totalbayar)</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@endsection
@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.0/dist/index.umd.min.js"></script>
<script>
    $(function () {
       $('.datatable-saldo').DataTable({
         'paging'      : true,
         'lengthChange': false,
         'searching'   : false,
         'ordering'    : false,
         'info'        : true,
         'autoWidth'   : false,
         'pageLength'  : 50
       })
     })
</script>
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
@endsection
