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
        <form action="{{ route('admin.pembayarans.jangka') }}" method="POST">
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
        <form action="{{ route('admin.pembayarans.periode') }}" method="POST">
            @csrf
            <div class="row mt-5">
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
            <table class="table table-bordered table-striped table-hover datatable-saldo">
                <thead>
                    <tr>
                        <th></th>
                        <th>Sales</th>
                        <th>Order</th>
                        <th>Tagihan</th>
                        <th>Retur</th>
                        <th>Pembayaran</th>
                        <th>Diskon</th>
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
                        <td class="text-right">@money($saldo->retur)</td>
                        <td class="text-right">@money($saldo->bayar)</td>
                        <td class="text-right">@money($saldo->diskon)</td>
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
                        <td class="text-right">@money($saldos->sum('retur'))</td>
                        <td class="text-right">@money($saldos->sum('bayar'))</td>
                        <td class="text-right">@money($saldos->sum('diskon'))</td>
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
