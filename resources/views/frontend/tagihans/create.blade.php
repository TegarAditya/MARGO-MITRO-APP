@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.tagihan.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.tagihans.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="order_id">{{ trans('cruds.tagihan.fields.order') }}</label>
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
                            <span class="help-block">{{ trans('cruds.tagihan.fields.order_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="saldo">{{ trans('cruds.tagihan.fields.saldo') }}</label>
                            <input class="form-control" type="number" name="saldo" id="saldo" value="{{ old('saldo', '0') }}" step="0.01" required>
                            @if($errors->has('saldo'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('saldo') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.tagihan.fields.saldo_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="salesperson_id">{{ trans('cruds.tagihan.fields.salesperson') }}</label>
                            <select class="form-control select2" name="salesperson_id" id="salesperson_id">
                                @foreach($salespeople as $id => $entry)
                                    <option value="{{ $id }}" {{ old('salesperson_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('salesperson'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('salesperson') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.tagihan.fields.salesperson_helper') }}</span>
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