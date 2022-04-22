@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.pembayaran.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.pembayarans.store") }}" enctype="multipart/form-data">
            @csrf

            @if (request('tagihan_id'))
                <input type="hidden" name="redirect" value="{{ url()->previous() }}" />
            @endif

            <div class="form-group">
                <label for="no_kwitansi">{{ trans('cruds.pembayaran.fields.no_kwitansi') }}</label>
                <input class="form-control {{ $errors->has('no_kwitansi') ? 'is-invalid' : '' }}" type="text" name="no_kwitansi" id="no_kwitansi" value="{{ old('no_kwitansi', '') }}" readonly placeholder="(Otomatis)">
                @if($errors->has('no_kwitansi'))
                    <span class="text-danger">{{ $errors->first('no_kwitansi') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.pembayaran.fields.no_kwitansi_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="tagihan_id">{{ trans('cruds.pembayaran.fields.tagihan') }}</label>
                <select class="form-control select2 {{ $errors->has('tagihan') ? 'is-invalid' : '' }}" name="tagihan_id" id="tagihan_id" required>
                    @foreach($tagihans as $id => $entry)
                        <option value="{{ $id }}" {{ old('tagihan_id') == $id ? 'selected' : (
                            request('tagihan_id') == $id ? 'selected' : ''
                        ) }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('tagihan'))
                    <span class="text-danger">{{ $errors->first('tagihan') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.pembayaran.fields.tagihan_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="nominal">{{ trans('cruds.pembayaran.fields.nominal') }}</label>
                <input class="form-control {{ $errors->has('nominal') ? 'is-invalid' : '' }}" type="number" name="nominal" id="nominal" value="{{ old('nominal', '0') }}" step="0.01" required>
                @if($errors->has('nominal'))
                    <span class="text-danger">{{ $errors->first('nominal') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.pembayaran.fields.nominal_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="diskon">{{ trans('cruds.pembayaran.fields.diskon') }}</label>
                <input class="form-control {{ $errors->has('diskon') ? 'is-invalid' : '' }}" type="number" name="diskon" id="diskon" value="{{ old('diskon', '0') }}" step="0.01">
                @if($errors->has('diskon'))
                    <span class="text-danger">{{ $errors->first('diskon') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.pembayaran.fields.diskon_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="bayar">{{ trans('cruds.pembayaran.fields.bayar') }}</label>
                <input class="form-control {{ $errors->has('bayar') ? 'is-invalid' : '' }}" type="number" name="bayar" id="bayar" value="{{ old('bayar', '0') }}" step="1" required>
                @if($errors->has('bayar'))
                    <span class="text-danger">{{ $errors->first('bayar') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.pembayaran.fields.bayar_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="tanggal">{{ trans('cruds.pembayaran.fields.tanggal') }}</label>
                <input class="form-control date {{ $errors->has('tanggal') ? 'is-invalid' : '' }}" type="text" name="tanggal" id="tanggal" value="{{ old('tanggal') }}" required>
                @if($errors->has('tanggal'))
                    <span class="text-danger">{{ $errors->first('tanggal') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.pembayaran.fields.tanggal_helper') }}</span>
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