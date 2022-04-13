@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.tagihanMovement.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-TagihanMovement">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.tagihanMovement.fields.reference') }}
                    </th>
                    <th>
                        {{ trans('cruds.tagihanMovement.fields.type') }}
                    </th>
                    <th>
                        {{ trans('cruds.tagihanMovement.fields.nominal') }}
                    </th>
                    <th>
                        {{ trans('cruds.tagihanMovement.fields.created_at') }}
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
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
  
  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.tagihan-movements.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'reference', name: 'reference' },
{ data: 'type', name: 'type' },
{ data: 'nominal', name: 'nominal' },
{ data: 'created_at', name: 'created_at' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 4, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-TagihanMovement').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection