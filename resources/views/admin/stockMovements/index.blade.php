@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('cruds.stockMovement.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <form id="filterform">
            <div class="row">
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
            </div>
            <div class="row mt-2 mb-3">
                <div class="col-12">
                    <div class="form-group">
                        <button class="btn btn-success" type="submit">
                            Cari
                        </button>
                    </div>
                </div>
            </div>


        </form>
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-StockMovement">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.stockMovement.fields.reference') }}
                    </th>
                    <th>
                        Sales
                    </th>
                    <th>
                        {{ trans('cruds.stockMovement.fields.product') }}
                    </th>
                    <th>
                        {{ trans('cruds.stockMovement.fields.quantity') }}
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
    ajax: "{{ route('admin.stock-movements.index') }}",
    columns: [
        { data: 'placeholder', name: 'placeholder' },
        { data: 'reference', name: 'reference', class:'text-center' },
        { data: 'sales', name: 'sales' },
        { data: 'product_name', name: 'product.name' },
        { data: 'quantity', name: 'quantity', class:'text-center' },
    ],
    orderCellsTop: true,
    order: [[ 4, 'desc' ]],
    pageLength: 25,
  };

    let table = $('.datatable-StockMovement').DataTable(dtOverrideGlobals);
    $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });
});
</script>
@endsection
