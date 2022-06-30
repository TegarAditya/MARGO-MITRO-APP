@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.buku.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.buku.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.buku.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.buku.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.buku.fields.description') }}</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description') }}</textarea>
                @if($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.buku.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="brand_id">{{ trans('cruds.buku.fields.brand') }}</label>
                <select class="form-control select2 {{ $errors->has('brand') ? 'is-invalid' : '' }}" name="brand_id" id="brand_id" required>
                    @foreach($brands as $id => $entry)
                        <option value="{{ $id }}" {{ old('brand_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('brand'))
                    <span class="text-danger">{{ $errors->first('brand') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.buku.fields.brand_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="jenjang_id">{{ trans('cruds.buku.fields.jenjang') }}</label>
                <select class="form-control select2 {{ $errors->has('jenjang') ? 'is-invalid' : '' }}" name="jenjang_id" id="jenjang_id" required>
                    @foreach($jenjang as $id => $entry)
                        <option value="{{ $id }}" {{ old('jenjang_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('jenjang'))
                    <span class="text-danger">{{ $errors->first('jenjang') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.buku.fields.jenjang_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="kelas_id">{{ trans('cruds.buku.fields.kelas') }}</label>
                <select class="form-control select2 {{ $errors->has('kelas') ? 'is-invalid' : '' }}" name="kelas_id" id="kelas_id" required>
                    @foreach($kelas as $id => $entry)
                        <option value="{{ $id }}" {{ old('kelas_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('kelas'))
                    <span class="text-danger">{{ $errors->first('kelas') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.buku.fields.kelas_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="halaman_id">{{ trans('cruds.buku.fields.halaman') }}</label>
                <select class="form-control select2 {{ $errors->has('halaman') ? 'is-invalid' : '' }}" name="halaman_id" id="halaman_id" required>
                    @foreach($halaman as $id => $entry)
                        <option value="{{ $id }}" {{ old('halaman_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('halaman'))
                    <span class="text-danger">{{ $errors->first('halaman') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.buku.fields.halaman_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="unit_id">{{ trans('cruds.buku.fields.unit') }}</label>
                <select class="form-control select2 {{ $errors->has('unit') ? 'is-invalid' : '' }}" name="unit_id" id="unit_id" required>
                    @foreach($units as $id => $entry)
                        <option value="{{ $id }}" {{ old('unit_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('unit'))
                    <span class="text-danger">{{ $errors->first('unit') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.buku.fields.unit_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="hpp">{{ trans('cruds.buku.fields.hpp') }}</label>
                <input class="form-control {{ $errors->has('hpp') ? 'is-invalid' : '' }}" type="number" name="hpp" id="hpp" value="{{ old('hpp', '') }}" step="0.01">
                @if($errors->has('hpp'))
                    <span class="text-danger">{{ $errors->first('hpp') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.buku.fields.hpp_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="price">{{ trans('cruds.buku.fields.price') }}</label>
                <input class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" type="number" name="price" id="price" value="{{ old('price', '0') }}" step="0.01" required>
                @if($errors->has('price'))
                    <span class="text-danger">{{ $errors->first('price') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.buku.fields.price_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="finishing_cost">{{ trans('cruds.buku.fields.finishing_cost') }}</label>
                <input class="form-control {{ $errors->has('finishing_cost') ? 'is-invalid' : '' }}" type="number" name="finishing_cost" id="finishing_cost" value="{{ old('finishing_cost', '') }}" step="0.01">
                @if($errors->has('finishing_cost'))
                    <span class="text-danger">{{ $errors->first('finishing_cost') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.buku.fields.finishing_cost_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="stock">{{ trans('cruds.buku.fields.stock') }}</label>
                <input class="form-control {{ $errors->has('stock') ? 'is-invalid' : '' }}" type="number" name="stock" id="stock" value="{{ old('stock', '0') }}" step="1" required>
                @if($errors->has('stock'))
                    <span class="text-danger">{{ $errors->first('stock') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.buku.fields.stock_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="min_stock">{{ trans('cruds.buku.fields.min_stock') }}</label>
                <input class="form-control {{ $errors->has('min_stock') ? 'is-invalid' : '' }}" type="number" name="min_stock" id="min_stock" value="{{ old('min_stock', '0') }}" step="1">
                @if($errors->has('min_stock'))
                    <span class="text-danger">{{ $errors->first('min_stock') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.buku.fields.min_stock_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="foto">{{ trans('cruds.buku.fields.foto') }}</label>
                <div class="needsclick dropzone {{ $errors->has('foto') ? 'is-invalid' : '' }}" id="foto-dropzone">
                </div>
                @if($errors->has('foto'))
                    <span class="text-danger">{{ $errors->first('foto') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.buku.fields.foto_helper') }}</span>
            </div>
            {{-- <div class="form-group">
                <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="status" value="0">
                    <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', 0) == 1 || old('status') === null ? 'checked' : '' }}>
                    <label class="form-check-label" for="status">{{ trans('cruds.buku.fields.status') }}</label>
                </div>
                @if($errors->has('status'))
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.buku.fields.status_helper') }}</span>
            </div> --}}
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')
<script>
    var uploadedFotoMap = {}
Dropzone.options.fotoDropzone = {
    url: '{{ route('admin.buku.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="foto[]" value="' + response.name + '">')
      uploadedFotoMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedFotoMap[file.name]
      }
      $('form').find('input[name="foto[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($product) && $product->foto)
      var files = {!! json_encode($product->foto) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="foto[]" value="' + file.file_name + '">')
        }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
@endsection
