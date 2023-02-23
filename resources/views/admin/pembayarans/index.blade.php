@extends('layouts.admin')
@section('content')
@can('pembayaran_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.pembayarans.general') }}">
                {{ trans('global.add') }} {{ trans('cruds.pembayaran.title_singular') }}
            </a>
            <a class="btn btn-warning" href="{{ route('admin.pembayarans.export') }}">
                Export Rekap Saldo
            </a>
        </div>
    </div>
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.pembayarans.jangka') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Generate Saldo</button>
            </form>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        Rekap Billing Semester Genap 2022/2023
    </div>

    <div class="card-body">
        <form action="{{ route('admin.pembayarans.periode') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label class="required" for="periode">Periode</label>
                        <select class="form-control select2" name="periode" id="periode" required>
                            @foreach($periode as $id => $entry)
                                <option value="{{ $id }}">{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('periode'))
                            <span class="text-danger">{{ $errors->first('periode') }}</span>
                        @endif
                        <span class="help-block"></span>
                    </div>
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
                $totalestimasi = 0;
                $totalpengambilan = 0;
                $totalretur = 0;
                $totalbayar = 0;
                $totaldiskon = 0;
                $totalpiutang = 0;
            @endphp
            <table class="table table-bordered table-striped table-hover datatable-saldo">
                <thead>
                    <tr>
                        <th></th>
                        <th>Sales</th>
                        <th>Estimasi</th>
                        <th>Pengambilan</th>
                        <th>Retur</th>
                        <th>Bayar</th>
                        <th>Diskon</th>
                        <th>Piutang</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($saldos as $saldo)
                    @php
                        $estimasi = $saldo->order_details->sum('total');
                        $pengambilan = $saldo->invoices->where('nominal', '>', 0)->sum('nominal');
                        $retur = abs($saldo->invoices->where('nominal', '<', 0)->sum('nominal'));
                        $bayar = $saldo->pembayarans->sum('bayar');
                        $diskon = $saldo->pembayarans->sum('diskon');
                        $piutang = $pengambilan - ($retur + $bayar + $diskon);
                        $totalestimasi += $estimasi;
                        $totalpengambilan += $pengambilan;
                        $totalretur += $retur;
                        $totalbayar += $bayar;
                        $totaldiskon += $diskon;
                        $totalpiutang += $piutang;
                    @endphp
                    <tr>
                        <td></td>
                        <td>{{ $saldo->name }}</td>
                        <td class="text-right">@money($estimasi)</td>
                        <td class="text-right">@money($pengambilan)</td>
                        <td class="text-right">@money($retur)</td>
                        <td class="text-right">@money($bayar)</td>
                        <td class="text-right">@money($diskon)</td>
                        <td class="text-right">@money($piutang)</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-center">
                            <strong>Total</strong>
                        </td>
                        <td class="text-right">@money($totalestimasi)</td>
                        <td class="text-right">@money($totalpengambilan)</td>
                        <td class="text-right">@money($totalretur)</td>
                        <td class="text-right">@money($totalbayar)</td>
                        <td class="text-right">@money($totaldiskon)</td>
                        <td class="text-right">@money($totalpiutang)</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('cruds.pembayaran.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Pembayaran">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.pembayaran.fields.tanggal') }}
                    </th>
                    <th>
                        {{ trans('cruds.pembayaran.fields.no_kwitansi') }}
                    </th>
                    <th>
                        {{ trans('cruds.pembayaran.fields.nominal') }}
                    </th>
                    <th>
                        {{ trans('cruds.pembayaran.fields.diskon') }}
                    </th>
                    <th>
                        {{ trans('cruds.pembayaran.fields.bayar') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.0/dist/index.umd.min.js"></script>
<script>
    $(function () {
//   let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

  let dtOverrideGlobals = {
    // buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.pembayarans.index') }}",
    columns: [
        { data: 'placeholder', name: 'placeholder' },
        { data: 'tanggal', name: 'tanggal', class: 'text-center' },
        { data: 'no_kwitansi', name: 'no_kwitansi', class: 'text-center' },
        // { data: 'tagihan_saldo', name: 'tagihan.saldo' },
        { data: 'nominal', name: 'nominal', class: 'text-right', render: function(value) { return numeral(value).format('$0,0'); } },
        { data: 'diskon', name: 'diskon', class: 'text-right', render: function(value) { return numeral(value).format('$0,0'); } },
        { data: 'bayar', name: 'bayar', class: 'text-right', render: function(value) { return numeral(value).format('$0,0'); } },
        { data: 'actions', name: '{{ trans('global.actions') }}', class: 'text-center' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-Pembayaran').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

});

</script>
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
