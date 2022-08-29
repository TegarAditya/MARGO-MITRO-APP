@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.product.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.buku.update", [$product->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.product.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.product.fields.description') }}</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description', $product->description) }}</textarea>
                @if($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="brand_id">{{ trans('cruds.product.fields.brand') }}</label>
                <select class="form-control select2 {{ $errors->has('brand') ? 'is-invalid' : '' }}" name="brand_id" id="brand_id">
                    @foreach($brands as $id => $entry)
                        <option value="{{ $id }}" {{ (old('brand_id') ? old('brand_id') : $product->brand->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('brand'))
                    <span class="text-danger">{{ $errors->first('brand') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.brand_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="isi_id">{{ trans('cruds.buku.fields.isi') }}</label>
                <select class="form-control select2 {{ $errors->has('isi') ? 'is-invalid' : '' }}" name="isi_id" id="isi_id" required>
                    @foreach($isi as $id => $entry)
                        <option value="{{ $id }}" {{ (old('isi_id') ? old('isi_id') : $product->isi->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('isi_id'))
                    <span class="text-danger">{{ $errors->first('isi_id') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.buku.fields.isi_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="jenjang_id">{{ trans('cruds.buku.fields.jenjang') }}</label>
                <select class="form-control select2 {{ $errors->has('jenjang') ? 'is-invalid' : '' }}" name="jenjang_id" id="jenjang_id" required>
                    @foreach($jenjang as $id => $entry)
                        <option value="{{ $id }}" {{ (old('jenjang_id') ? old('jenjang_id') : $product->jenjang->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('jenjang'))
                    <span class="text-danger">{{ $errors->first('jenjang') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.buku.fields.jenjang_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="unit_id">{{ trans('cruds.product.fields.unit') }}</label>
                <select class="form-control select2 {{ $errors->has('unit') ? 'is-invalid' : '' }}" name="unit_id" id="unit_id" required>
                    @foreach($units as $id => $entry)
                        <option value="{{ $id }}" {{ (old('unit_id') ? old('unit_id') : $product->unit->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('unit'))
                    <span class="text-danger">{{ $errors->first('unit') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.unit_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.product.fields.tipe_pg') }}</label>
                <select class="form-control {{ $errors->has('tipe_pg') ? 'is-invalid' : '' }}" name="tipe_pg" id="tipe_pg">
                    <option value disabled {{ old('tipe_pg', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Product::TIPE_PG_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('tipe_pg', $product->tipe_pg) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('tipe_pg'))
                    <span class="text-danger">{{ $errors->first('tipe_pg') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.tipe_pg_helper') }}</span>
            </div>
            <div class="form-group" id="div_pg">
                <label class="required" for="pg_id">Pegangan Guru(PG)</label>
                <select class="form-control select2 {{ $errors->has('pg_id') ? 'is-invalid' : '' }}" name="pg_id" id="pg_id">
                    @foreach($pg as $id => $entry)
                        <option value="{{ $id }}" {{ (old('pg_id') ? old('pg_id') : $product->pg->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('pg_id'))
                    <span class="text-danger">{{ $errors->first('pg_id') }}</span>
                @endif
                <span class="help-block"></span>
            </div>
            <div class="form-group" id="div_kunci">
                <label class="required" for="kunci_id">Kunci Jawaban</label>
                <select class="form-control select2 {{ $errors->has('kunci_id') ? 'is-invalid' : '' }}" name="kunci_id" id="kunci_id">
                    @foreach($kunci as $id => $entry)
                        <option value="{{ $id }}" {{ (old('kunci_id') ? old('kunci_id') : $product->kunci->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('kunci_id'))
                    <span class="text-danger">{{ $errors->first('kunci_id') }}</span>
                @endif
                <span class="help-block"></span>
            </div>
            <div class="form-group">
                <label for="hpp">{{ trans('cruds.product.fields.hpp') }}</label>
                <input class="form-control {{ $errors->has('hpp') ? 'is-invalid' : '' }}" type="number" name="hpp" id="hpp" value="{{ old('hpp', $product->hpp) }}" step="0.01">
                @if($errors->has('hpp'))
                    <span class="text-danger">{{ $errors->first('hpp') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.hpp_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="price">{{ trans('cruds.product.fields.price') }}</label>
                <input class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" type="number" name="price" id="price" value="{{ old('price', $product->price) }}" step="0.01" required>
                @if($errors->has('price'))
                    <span class="text-danger">{{ $errors->first('price') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.price_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="finishing_cost">{{ trans('cruds.product.fields.finishing_cost') }}</label>
                <input class="form-control {{ $errors->has('finishing_cost') ? 'is-invalid' : '' }}" type="number" name="finishing_cost" id="finishing_cost" value="{{ old('finishing_cost', $product->finishing_cost) }}" step="0.01">
                @if($errors->has('finishing_cost'))
                    <span class="text-danger">{{ $errors->first('finishing_cost') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.finishing_cost_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="stock">{{ trans('cruds.product.fields.stock') }}</label>
                <input class="form-control {{ $errors->has('stock') ? 'is-invalid' : '' }}" type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" step="1" required>
                @if($errors->has('stock'))
                    <span class="text-danger">{{ $errors->first('stock') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.stock_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="min_stock">{{ trans('cruds.product.fields.min_stock') }}</label>
                <input class="form-control {{ $errors->has('min_stock') ? 'is-invalid' : '' }}" type="number" name="min_stock" id="min_stock" value="{{ old('min_stock', $product->min_stock) }}" step="1">
                @if($errors->has('min_stock'))
                    <span class="text-danger">{{ $errors->first('min_stock') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.min_stock_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="foto">{{ trans('cruds.product.fields.foto') }}</label>
                <div class="needsclick dropzone {{ $errors->has('foto') ? 'is-invalid' : '' }}" id="foto-dropzone">
                </div>
                @if($errors->has('foto'))
                    <span class="text-danger">{{ $errors->first('foto') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.foto_helper') }}</span>
            </div>
            {{-- <div class="form-group">
                <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="status" value="0">
                    <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ $product->status || old('status', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="status">{{ trans('cruds.product.fields.status') }}</label>
                </div>
                @if($errors->has('status'))
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.status_helper') }}</span>
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
<script>
(function($) {
    $(function() {
        var tipe_pg = $('#tipe_pg');
        var pg = $('#div_pg');
        var kunci = $('#div_kunci');

        tipe_pg.on('change', function(e) {
            let value = e.target.value;
            if (value !== 'non_pg') {
                pg.hide();
                kunci.hide();
                $('#pg_id').val('').trigger('change');
                $('#kunci_id').val('').trigger('change');
            } else {
                pg.show();
                kunci.show();
            }
        }).trigger('change');
    });
})(jQuery);
</script>
@endsection
