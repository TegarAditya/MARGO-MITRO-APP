@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.invoice.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.invoices.update", [$invoice->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="no_suratjalan">{{ trans('cruds.invoice.fields.no_suratjalan') }}</label>
                <input class="form-control {{ $errors->has('no_suratjalan') ? 'is-invalid' : '' }}" type="text" name="no_suratjalan" id="no_suratjalan" value="{{ old('no_suratjalan', $invoice->no_suratjalan) }}" required>
                @if($errors->has('no_suratjalan'))
                    <span class="text-danger">{{ $errors->first('no_suratjalan') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.invoice.fields.no_suratjalan_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="no_invoice">{{ trans('cruds.invoice.fields.no_invoice') }}</label>
                <input class="form-control {{ $errors->has('no_invoice') ? 'is-invalid' : '' }}" type="text" name="no_invoice" id="no_invoice" value="{{ old('no_invoice', $invoice->no_invoice) }}" required>
                @if($errors->has('no_invoice'))
                    <span class="text-danger">{{ $errors->first('no_invoice') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.invoice.fields.no_invoice_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="order_id">{{ trans('cruds.invoice.fields.order') }}</label>
                <select class="form-control select2 {{ $errors->has('order') ? 'is-invalid' : '' }}" name="order_id" id="order_id" required>
                    @foreach($orders as $id => $entry)
                        <option value="{{ $id }}" {{ (old('order_id') ? old('order_id') : $invoice->order->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('order'))
                    <span class="text-danger">{{ $errors->first('order') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.invoice.fields.order_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="date">{{ trans('cruds.invoice.fields.date') }}</label>
                <input class="form-control date {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="date" id="date" value="{{ old('date', $invoice->date) }}" required>
                @if($errors->has('date'))
                    <span class="text-danger">{{ $errors->first('date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.invoice.fields.date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="nominal">{{ trans('cruds.invoice.fields.nominal') }}</label>
                <input class="form-control {{ $errors->has('nominal') ? 'is-invalid' : '' }}" type="number" name="nominal" id="nominal" value="{{ old('nominal', $invoice->nominal) }}" step="1">
                @if($errors->has('nominal'))
                    <span class="text-danger">{{ $errors->first('nominal') }}</span>
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



@endsection