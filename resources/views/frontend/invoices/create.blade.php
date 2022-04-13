@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.invoice.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.invoices.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="no_suratjalan">{{ trans('cruds.invoice.fields.no_suratjalan') }}</label>
                            <input class="form-control" type="text" name="no_suratjalan" id="no_suratjalan" value="{{ old('no_suratjalan', '') }}" required>
                            @if($errors->has('no_suratjalan'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('no_suratjalan') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.invoice.fields.no_suratjalan_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="no_invoice">{{ trans('cruds.invoice.fields.no_invoice') }}</label>
                            <input class="form-control" type="text" name="no_invoice" id="no_invoice" value="{{ old('no_invoice', '') }}" required>
                            @if($errors->has('no_invoice'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('no_invoice') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.invoice.fields.no_invoice_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="order_id">{{ trans('cruds.invoice.fields.order') }}</label>
                            <select class="form-control select2" name="order_id" id="order_id" required>
                                @foreach($orders as $id => $entry)
                                    <option value="{{ $id }}" {{ old('order_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('order'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('order') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.invoice.fields.order_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="date">{{ trans('cruds.invoice.fields.date') }}</label>
                            <input class="form-control date" type="text" name="date" id="date" value="{{ old('date') }}" required>
                            @if($errors->has('date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.invoice.fields.date_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="nominal">{{ trans('cruds.invoice.fields.nominal') }}</label>
                            <input class="form-control" type="number" name="nominal" id="nominal" value="{{ old('nominal', '0') }}" step="1">
                            @if($errors->has('nominal'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nominal') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.invoice.fields.nominal_helper') }}</span>
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