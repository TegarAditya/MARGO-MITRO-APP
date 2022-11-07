@extends('layouts.admin')
@section('content')
@can('custom_price_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.custom-prices.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.customPrice.title_singular') }}
            </a>
            {{-- <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button> --}}
            <button class="btn btn-primary" data-toggle="modal" data-target="#importModal">
                Import
            </button>
            @include('csvImport.import_modal', ['model' => 'CustomPrice', 'route' => 'admin.custom-prices.import'])
            {{-- @include('csvImport.modal', ['model' => 'CustomPrice', 'route' => 'admin.custom-prices.parseCsvImport']) --}}
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.customPrice.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <form id="filterform">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="sales_id">{{ trans('cruds.customPrice.fields.sales') }}</label>
                        <select class="form-control select2 {{ $errors->has('sales') ? 'is-invalid' : '' }}" name="sales_id" id="sales_id">
                            @foreach($sales as $id => $entry)
                                <option value="{{ $id }}" {{ old('sales_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('sales'))
                            <span class="text-danger">{{ $errors->first('sales') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.customPrice.fields.sales_helper') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="kategori_id">{{ trans('cruds.customPrice.fields.kategori') }}</label>
                        <select class="form-control select2 {{ $errors->has('kategori') ? 'is-invalid' : '' }}" name="kategori_id" id="kategori_id">
                            @foreach($kategoris as $id => $entry)
                                <option value="{{ $id }}" {{ old('kategori_id') == $id ? 'selected' : '' }}>{{ $entry }} Halaman</option>
                            @endforeach
                        </select>
                        @if($errors->has('kategori'))
                            <span class="text-danger">{{ $errors->first('kategori') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.customPrice.fields.kategori_helper') }}</span>
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <button class="btn btn-success" type="submit">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-CustomPrice">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.customPrice.fields.nama') }}
                    </th>
                    <th>
                        {{ trans('cruds.customPrice.fields.sales') }}
                    </th>
                    <th>
                        {{ trans('cruds.customPrice.fields.kategori') }}
                    </th>
                    {{-- <th>
                        {{ trans('cruds.category.fields.type') }}
                    </th> --}}
                    <th>
                        {{ trans('cruds.customPrice.fields.harga') }}
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
@can('custom_price_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.custom-prices.massDestroy') }}",
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
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: {
        url: "{{ route('admin.custom-prices.index') }}",
        data: function(data) {
            data.sales = $('#sales_id').val(),
            data.halaman = $('#kategori_id').val()
        }
    },
    columns: [
        { data: 'placeholder', name: 'placeholder' },
        { data: 'nama', name: 'nama', class: 'text-left' },
        { data: 'sales', name: 'sales', class:'text-center' },
        { data: 'kategori_name', name: 'kategori.name', class:'text-center' },
        { data: 'harga', name: 'harga', class: 'text-right', render: function(value) { return numeral(value).format('$0,0'); } },
        { data: 'actions', name: '{{ trans('global.actions') }}', class:'text-center' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-CustomPrice').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

  $("#filterform").submit(function(event) {
        event.preventDefault();
        table.ajax.reload();
    });

});

</script>
@endsection
