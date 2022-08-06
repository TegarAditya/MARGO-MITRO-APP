@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.customPrice.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.custom-prices.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="nama">{{ trans('cruds.customPrice.fields.nama') }}</label>
                <input class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}" type="text" name="nama" id="nama" value="{{ old('nama', '') }}" required>
                @if($errors->has('nama'))
                    <span class="text-danger">{{ $errors->first('nama') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.customPrice.fields.nama_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="kategori_id">{{ trans('cruds.customPrice.fields.kategori') }}</label>
                <select class="form-control select2 {{ $errors->has('kategori') ? 'is-invalid' : '' }}" name="kategori_id" id="kategori_id" required>
                    @foreach($kategoris as $id => $entry)
                        <option value="{{ $id }}" {{ old('kategori_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('kategori'))
                    <span class="text-danger">{{ $errors->first('kategori') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.customPrice.fields.kategori_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="harga">{{ trans('cruds.customPrice.fields.harga') }}</label>
                <input class="form-control {{ $errors->has('harga') ? 'is-invalid' : '' }}" type="number" name="harga" id="harga" value="{{ old('harga', '0') }}" step="0.01" required>
                @if($errors->has('harga'))
                    <span class="text-danger">{{ $errors->first('harga') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.customPrice.fields.harga_helper') }}</span>
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