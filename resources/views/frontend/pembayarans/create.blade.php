@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.pembayaran.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.pembayarans.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="no_kwitansi">{{ trans('cruds.pembayaran.fields.no_kwitansi') }}</label>
                            <input class="form-control" type="text" name="no_kwitansi" id="no_kwitansi" value="{{ old('no_kwitansi', '') }}">
                            @if($errors->has('no_kwitansi'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('no_kwitansi') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.pembayaran.fields.no_kwitansi_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="tagihan_id">{{ trans('cruds.pembayaran.fields.tagihan') }}</label>
                            <select class="form-control select2" name="tagihan_id" id="tagihan_id" required>
                                @foreach($tagihans as $id => $entry)
                                    <option value="{{ $id }}" {{ old('tagihan_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('tagihan'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('tagihan') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.pembayaran.fields.tagihan_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="nominal">{{ trans('cruds.pembayaran.fields.nominal') }}</label>
                            <input class="form-control" type="number" name="nominal" id="nominal" value="{{ old('nominal', '0') }}" step="0.01" required>
                            @if($errors->has('nominal'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nominal') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.pembayaran.fields.nominal_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="diskon">{{ trans('cruds.pembayaran.fields.diskon') }}</label>
                            <input class="form-control" type="number" name="diskon" id="diskon" value="{{ old('diskon', '0') }}" step="0.01">
                            @if($errors->has('diskon'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('diskon') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.pembayaran.fields.diskon_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="bayar">{{ trans('cruds.pembayaran.fields.bayar') }}</label>
                            <input class="form-control" type="number" name="bayar" id="bayar" value="{{ old('bayar', '0') }}" step="1" required>
                            @if($errors->has('bayar'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('bayar') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.pembayaran.fields.bayar_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="tanggal">{{ trans('cruds.pembayaran.fields.tanggal') }}</label>
                            <input class="form-control date" type="text" name="tanggal" id="tanggal" value="{{ old('tanggal') }}" required>
                            @if($errors->has('tanggal'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('tanggal') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection