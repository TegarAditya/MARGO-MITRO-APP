@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.product.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <form id="filterform">
            <div class="row">
                {{-- <div class="col-6">
                    <div class="form-group">
                        <label for="name">{{ trans('cruds.buku.fields.name') }}</label>
                        <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}">
                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.buku.fields.name_helper') }}</span>
                    </div>
                </div> --}}
                <div class="col-4">
                    <div class="form-group">
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
                </div>
                <div class="col-4">
                    <div class="form-group">
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
                </div>
                <div class="col-4">
                    <div class="form-group">
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
                </div>
                <div class="col-4">
                    <div class="form-group">
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
                </div>
                <div class="col-4">
                    <div class="form-group">
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
                <div class="col-4">
                    <div class="form-group">
                        <label for="semester_id">{{ trans('cruds.buku.fields.semester') }}</label>
                        <select class="form-control select2 {{ $errors->has('semester_id') ? 'is-invalid' : '' }}" name="semester_id" id="semester_id">
                            @foreach($semester as $id => $entry)
                                <option value="{{ $id }}" {{ old('semester_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('semester_id'))
                            <span class="text-danger">{{ $errors->first('semester_id') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.buku.fields.semester_helper') }}</span>
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
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Product">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.product.fields.category') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.hpp') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.price') }}
                    </th>
                    <th>
                        {{ trans('cruds.product.fields.stock') }}
                    </th>
                    <th>
                        Stock Value
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
    ajax: {
        url: "{{ route('admin.stock-opnames.index') }}",
        data: function(data) {
            data.brand = $('#brand_id').val(),
            data.isi = $('#isi_id').val(),
            data.jenjang = $('#jenjang_id').val(),
            data.kelas = $('#kelas_id').val(),
            data.halaman = $('#halaman_id').val()
            data.semester = $('#semester_id').val()
        }
    },
    columns: [
        { data: 'placeholder', name: 'placeholder' },
        { data: 'category_name', name: 'category.name', class: 'text-center' },
        { data: 'name', name: 'name' },
        { data: 'hpp', name: 'hpp', class: 'text-right' },
        { data: 'price', name: 'price', class: 'text-right' },
        { data: 'stock', name: 'stock', class: 'text-center' },
        { data: 'value', name: 'value', class: 'text-right' },
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
