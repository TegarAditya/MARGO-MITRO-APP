@extends('layouts.admin')
@section('content')
@can('product_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.buku.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.buku.title_singular') }}
            </a>
            <button class="btn btn-primary" data-toggle="modal" data-target="#importModal">
                Import
            </button>
            <a class="btn btn-success" href="{{ public_path('template/Template_Import_Buku.csv') }}">
                Download Template Import
            </a>
            @include('csvImport.import_modal', ['model' => 'Product', 'route' => 'admin.buku.import'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.buku.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <form id="filterform">
            <div class="row">
                <div class="form-group col-12">
                    <label for="name">{{ trans('cruds.buku.fields.name') }}</label>
                    <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}">
                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.buku.fields.name_helper') }}</span>
                </div>
                <div class="form-group col-6">
                    <label for="brand_id">{{ trans('cruds.buku.fields.brand') }}</label>
                    <select class="form-control select2 {{ $errors->has('brand') ? 'is-invalid' : '' }}" name="brand_id" id="brand_id">
                        @foreach($brands as $id => $entry)
                            <option value="{{ $id }}" {{ old('brand_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('brand'))
                        <span class="text-danger">{{ $errors->first('brand') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.buku.fields.brand_helper') }}</span>
                </div>
                <div class="form-group col-6">
                    <label for="isi_id">{{ trans('cruds.buku.fields.isi') }}</label>
                    <select class="form-control select2 {{ $errors->has('isi') ? 'is-invalid' : '' }}" name="isi_id" id="isi_id">
                        @foreach($isi as $id => $entry)
                            <option value="{{ $id }}" {{ old('isi_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('isi'))
                        <span class="text-danger">{{ $errors->first('isi') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.buku.fields.isi_helper') }}</span>
                </div>
                <div class="form-group col-6">
                    <label for="jenjang_id">{{ trans('cruds.buku.fields.jenjang') }}</label>
                    <select class="form-control select2 {{ $errors->has('jenjang') ? 'is-invalid' : '' }}" name="jenjang_id" id="jenjang_id">
                        @foreach($jenjang as $id => $entry)
                            <option value="{{ $id }}" {{ old('jenjang_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('jenjang'))
                        <span class="text-danger">{{ $errors->first('jenjang') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.buku.fields.jenjang_helper') }}</span>
                </div>

                <div class="form-group col-6">
                    <label for="kelas_id">{{ trans('cruds.buku.fields.kelas') }}</label>
                    <select class="form-control select2 {{ $errors->has('kelas') ? 'is-invalid' : '' }}" name="kelas_id" id="kelas_id">
                        @foreach($kelas as $id => $entry)
                            <option value="{{ $id }}" {{ old('kelas_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('kelas'))
                        <span class="text-danger">{{ $errors->first('kelas') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.buku.fields.kelas_helper') }}</span>
                </div>
                <div class="form-group col-6">
                    <label for="halaman_id">{{ trans('cruds.buku.fields.halaman') }}</label>
                    <select class="form-control select2 {{ $errors->has('halaman') ? 'is-invalid' : '' }}" name="halaman_id" id="halaman_id">
                        @foreach($halaman as $id => $entry)
                            <option value="{{ $id }}" {{ old('halaman_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('halaman'))
                        <span class="text-danger">{{ $errors->first('halaman') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.buku.fields.halaman_helper') }}</span>
                </div>
            </div>

            <div class="form-group mt-3">
                <button class="btn btn-success" type="submit">
                    Cari
                </button>
            </div>
        </form>
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Product">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.buku.fields.brand') }} - Isi
                    </th>
                    <th>
                        {{ trans('cruds.buku.fields.jenjang') }}
                    </th>
                    {{-- <th>
                        {{ trans('cruds.buku.fields.kelas') }}
                    </th> --}}
                    <th>
                        {{ trans('cruds.buku.fields.name') }}
                    </th>
                    {{-- <th>
                        {{ trans('cruds.buku.fields.halaman') }}
                    </th> --}}
                    <th>
                        {{ trans('cruds.buku.fields.hpp') }}
                    </th>
                    {{-- <th>
                        {{ trans('cruds.buku.fields.price') }}
                    </th> --}}
                    <th>
                        {{ trans('cruds.buku.fields.stock') }}
                    </th>
                    <th width="12%">
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
@can('product_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.buku.massDestroy') }}",
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
        url: "{{ route('admin.buku.index') }}",
        data: function(data) {
            data.name = $('#name').val(),
            data.brand = $('#brand_id').val(),
            data.isi = $('#isi_id').val(),
            data.jenjang = $('#jenjang_id').val(),
            data.kelas = $('#kelas_id').val(),
            data.halaman = $('#halaman_id').val()
        }
    },
    columns: [
        { data: 'placeholder', name: 'placeholder' },
        { data: 'brand_name', name: 'brand.name', class: 'text-center' },
        { data: 'jenjang_name', name: 'jenjang_name', class: 'text-center' },
        // { data: 'kelas_name', name: 'kelas_name' },
        { data: 'name', name: 'name' },
        // { data: 'halaman_name', name: 'halaman_name' },
        { data: 'hpp', name: 'hpp', class: 'text-right' },
        // { data: 'price', name: 'price' },
        { data: 'stock', name: 'stock', class: 'text-center' },
        { data: 'actions', name: '{{ trans('global.actions') }}', class: 'text-center' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-Product').DataTable(dtOverrideGlobals);
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
