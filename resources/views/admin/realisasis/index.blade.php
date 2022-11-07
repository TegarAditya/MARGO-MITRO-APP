@extends('layouts.admin')
@section('content')
@can('invoice_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.realisasis.create') }}">
                {{ trans('global.add') }} Realisasi
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        Realisasi {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Invoice">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    {{-- <th>
                        ID
                    </th> --}}
                    <th>
                        No. PO
                    </th>
                    <th>
                        No. Realisasi
                    </th>
                    <th>
                        {{ trans('cruds.invoice.fields.date') }}
                    </th>
                    <th>
                        {{ trans('cruds.invoice.fields.nominal') }}
                    </th>
                    <th>
                        Sudah dibayar ?
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
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('invoice_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.realisasis.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
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

  let dtOverrideGlobals = {
    // buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.realisasis.index') }}",
    columnDefs: [
    {
        "targets": 4,
        "className": "text-right",
    },
    {
        "targets": 5,
        "className": "text-center",
    }],
    columns: [
      { data: 'placeholder', name: 'placeholder' },
// { data: 'id', name: 'id' },
{ data: 'finishing_order', name: 'finishing_order' },
{ data: 'no_realisasi', name: 'no_realisasi' },
{ data: 'date', name: 'date' },
{ data: 'nominal', name: 'nominal', render: function(value) { return numeral(value).format('$0,0'); } },
{ data: 'lunas', name: 'lunas' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-Invoice').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

  $('body').on('click', '.button-lunas', function () {
        event.preventDefault();
        const id = $(this).data('id');
        swal({
            title: 'Apakah Kwitansi sudah dibayarkan ?',
            text: 'Apakah Kwitansi sudah dibayarkan ?',
            icon: 'warning',
            buttons: ["Cancel", "Yes!"],
            showSpinner: true
        }).then(function(value) {
            if (value) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.realisasis.paid') }}",
                    data: {
                        id: id
                    },
                    success: function (response) {
                        if (response.status == 'success') {
                            table.ajax.reload();
                            swal("Success", response.message, "success");
                        } else {
                            swal("Warning!", response.message, 'error');
                        }
                    }
                });
            }
        });
    });

});

</script>
@endsection
