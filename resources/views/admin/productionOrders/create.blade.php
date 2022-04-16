@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.productionOrder.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.production-orders.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="productionperson_id">{{ trans('cruds.productionOrder.fields.productionperson') }}</label>
                <select class="form-control select2 {{ $errors->has('productionperson') ? 'is-invalid' : '' }}" name="productionperson_id" id="productionperson_id" required>
                    @foreach($productionpeople as $id => $entry)
                        <option value="{{ $id }}" {{ old('productionperson_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('productionperson'))
                    <span class="text-danger">{{ $errors->first('productionperson') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productionOrder.fields.productionperson_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="date">{{ trans('cruds.productionOrder.fields.date') }}</label>
                <input class="form-control date {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="date" id="date" value="{{ old('date') }}" required>
                @if($errors->has('date'))
                    <span class="text-danger">{{ $errors->first('date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.productionOrder.fields.date_helper') }}</span>
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