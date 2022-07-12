@extends('layouts.admin')
@section('content')
@can('pembayaran_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.pembayarans.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.pembayaran.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.pembayaran.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Pembayaran">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.pembayaran.fields.no_kwitansi') }}
                        </th>
                        <th>
                            {{ trans('cruds.pembayaran.fields.tagihan') }}
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
                            {{ trans('cruds.pembayaran.fields.tanggal') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pembayarans as $key => $pembayaran)
                        <tr data-entry-id="{{ $pembayaran->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $pembayaran->no_kwitansi ?? '' }}
                            </td>
                            <td>
                                @money($pembayaran->tagihan->saldo)
                            </td>
                            <td>
                                @money($pembayaran->nominal)
                            </td>
                            <td>
                                @money($pembayaran->diskon)
                            </td>
                            <td>
                                @money($pembayaran->bayar)
                            </td>
                            <td>
                                {{ $pembayaran->tanggal ?? '' }}
                            </td>
                            <td>
                                @can('pembayaran_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.orders.show', $pembayaran->order->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('pembayaran_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.pembayarans.edit', $pembayaran->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('pembayaran_delete')
                                    <form action="{{ route('admin.pembayarans.destroy', $pembayaran->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('pembayaran_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.pembayarans.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Pembayaran:not(.ajaxTable)').DataTable()
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
