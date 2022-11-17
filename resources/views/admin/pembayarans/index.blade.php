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
@endcan
<div class="card">
    <div class="card-header">
        Laporan Pembayaran
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable-saldo">
                <thead>
                    <tr>
                        <th></th>
                        <th>Sales</th>
                        <th>Order</th>
                        <th>Tagihan</th>
                        <th>Pembayaran</th>
                        <th>Hutang</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($saldos as $saldo)
                    <tr>
                        <td></td>
                        <td>{{ $saldo->name }}</td>
                        <td class="text-right">@money($saldo->pesanan)</td>
                        <td class="text-right">@money($saldo->tagihan)</td>
                        <td class="text-right">@money($saldo->bayar)</td>
                        <td class="text-right">@money($saldo->tagihan - $saldo->bayar)</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-center">
                            <strong>Total</strong>
                        </td>
                        <td class="text-right">@money($saldos->sum('pesanan'))</td>
                        <td class="text-right">@money($saldos->sum('tagihan'))</td>
                        <td class="text-right">@money($saldos->sum('bayar'))</td>
                        <td class="text-right">@money($saldos->sum('tagihan') - $saldos->sum('bayar'))</td>
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
@endsection
