@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.tagihanMovement.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-TagihanMovement">
                            <thead>
                                <tr>
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
                            <tbody>
                                @foreach($tagihanMovements as $key => $tagihanMovement)
                                    <tr data-entry-id="{{ $tagihanMovement->id }}">
                                        <td>
                                            {{ $tagihanMovement->reference ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\TagihanMovement::TYPE_SELECT[$tagihanMovement->type] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $tagihanMovement->nominal ?? '' }}
                                        </td>
                                        <td>
                                            {{ $tagihanMovement->created_at ?? '' }}
                                        </td>
                                        <td>
                                            @can('tagihan_movement_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.tagihan-movements.show', $tagihanMovement->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan



                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
  
  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 3, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-TagihanMovement:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection