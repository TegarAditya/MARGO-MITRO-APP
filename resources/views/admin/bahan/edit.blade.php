@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.bahan.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.bahan.update", [$product->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.bahan.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.bahan.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.bahan.fields.description') }}</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description', $product->description) }}</textarea>
                @if($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.bahan.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="unit_id">{{ trans('cruds.bahan.fields.unit') }}</label>
                <select class="form-control select2 {{ $errors->has('unit') ? 'is-invalid' : '' }}" name="unit_id" id="unit_id" required>
                    @foreach($units as $id => $entry)
                        <option value="{{ $id }}" {{ (old('unit_id') ? old('unit_id') : $product->unit->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('unit'))
                    <span class="text-danger">{{ $errors->first('unit') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.bahan.fields.unit_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="hpp">{{ trans('cruds.bahan.fields.hpp') }}</label>
                <input class="form-control {{ $errors->has('hpp') ? 'is-invalid' : '' }}" type="number" name="hpp" id="hpp" value="{{ old('hpp', $product->hpp) }}" step="0.01">
                @if($errors->has('hpp'))
                    <span class="text-danger">{{ $errors->first('hpp') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.bahan.fields.hpp_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="price">{{ trans('cruds.bahan.fields.price') }}</label>
                <input class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" type="number" name="price" id="price" value="{{ old('price', $product->price) }}" step="0.01" required>
                @if($errors->has('price'))
                    <span class="text-danger">{{ $errors->first('price') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.bahan.fields.price_helper') }}</span>
            </div>
            {{-- <div class="form-group">
                <label for="finishing_cost">{{ trans('cruds.bahan.fields.finishing_cost') }}</label>
                <input class="form-control {{ $errors->has('finishing_cost') ? 'is-invalid' : '' }}" type="number" name="finishing_cost" id="finishing_cost" value="{{ old('finishing_cost', $product->finishing_cost) }}" step="0.01">
                @if($errors->has('finishing_cost'))
                    <span class="text-danger">{{ $errors->first('finishing_cost') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.bahan.fields.finishing_cost_helper') }}</span>
            </div> --}}
            <div class="form-group">
                <label class="required" for="stock">{{ trans('cruds.bahan.fields.stock') }}</label>
                <input class="form-control {{ $errors->has('stock') ? 'is-invalid' : '' }}" type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" step="1" required>
                @if($errors->has('stock'))
                    <span class="text-danger">{{ $errors->first('stock') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.bahan.fields.stock_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="min_stock">{{ trans('cruds.bahan.fields.min_stock') }}</label>
                <input class="form-control {{ $errors->has('min_stock') ? 'is-invalid' : '' }}" type="number" name="min_stock" id="min_stock" value="{{ old('min_stock', $product->min_stock) }}" step="1">
                @if($errors->has('min_stock'))
                    <span class="text-danger">{{ $errors->first('min_stock') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.bahan.fields.min_stock_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="foto">{{ trans('cruds.bahan.fields.foto') }}</label>
                <div class="needsclick dropzone {{ $errors->has('foto') ? 'is-invalid' : '' }}" id="foto-dropzone">
                </div>
                @if($errors->has('foto'))
                    <span class="text-danger">{{ $errors->first('foto') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.bahan.fields.foto_helper') }}</span>
            </div>
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
    url: '{{ route('admin.bahan.storeMedia') }}',
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
