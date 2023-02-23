@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Laporan Pembayaran {{ $title }}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable-saldo">
                <thead>
                    <tr>
                        <th></th>
                        <th>Sales</th>
                        <th>Saldo Awal</th>
                        <th>Pengambilan</th>
                        <th>Retur</th>
                        <th>Bayar</th>
                        <th>Diskon</th>
                        <th>Saldo Akhir</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($saldos as $saldo)
                    <tr>
                        <td></td>
                        <td>{{ $saldo->sales->name}}</td>
                        <td class="text-right">@money($saldo->saldo_awal)</td>
                        <td class="text-right">@money($saldo->tagihan)</td>
                        <td class="text-right">@money($saldo->retur)</td>
                        <td class="text-right">@money($saldo->bayar)</td>
                        <td class="text-right">@money($saldo->diskon)</td>
                        <td class="text-right">@money($saldo->saldo_akhir)</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-center">
                            <strong>Total</strong>
                        </td>
                        <td class="text-right">@money($saldos->sum('saldo_awal'))</td>
                        <td class="text-right">@money($saldos->sum('tagihan'))</td>
                        <td class="text-right">@money($saldos->sum('retur'))</td>
                        <td class="text-right">@money($saldos->sum('bayar'))</td>
                        <td class="text-right">@money($saldos->sum('diskon'))</td>
                        <td class="text-right">@money($saldos->sum('saldo_akhir'))</td>
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
