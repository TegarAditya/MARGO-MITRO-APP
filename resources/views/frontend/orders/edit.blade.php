@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.order.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.orders.update", [$order->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="date">{{ trans('cruds.order.fields.date') }}</label>
                            <input class="form-control date" type="text" name="date" id="date" value="{{ old('date', $order->date) }}" required>
                            @if($errors->has('date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.order.fields.date_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="salesperson_id">{{ trans('cruds.order.fields.salesperson') }}</label>
                            <select class="form-control select2" name="salesperson_id" id="salesperson_id" required>
                                @foreach($salespeople as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('salesperson_id') ? old('salesperson_id') : $order->salesperson->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('salesperson'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('salesperson') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.order.fields.salesperson_helper') }}</span>
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