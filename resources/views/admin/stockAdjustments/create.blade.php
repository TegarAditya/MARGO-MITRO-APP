@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.stockAdjustment.title_singular') }}
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route("admin.stock-adjustments.create") }}" enctype="multipart/form-data" id="pilihForm">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="isi">Isi</label>
                        <select class="form-control select2 {{ $errors->has('isi') ? 'is-invalid' : '' }}" name="isi" id="isi" required>
                            @foreach($isi as $id => $entry)
                                <option value="{{ $id }}" {{ request('isi') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('isi'))
                            <span class="text-danger">{{ $errors->first('isi') }}</span>
                        @endif
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="cover">Cover</label>
                        <select class="form-control select2 {{ $errors->has('cover') ? 'is-invalid' : '' }}" name="cover" id="cover" required>
                            @foreach($covers as $id => $entry)
                                <option value="{{ $id }}" {{ request('cover') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('cover'))
                            <span class="text-danger">{{ $errors->first('cover') }}</span>
                        @endif
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="jenjang">{{ trans('cruds.buku.fields.jenjang') }} {{ old('jenjang') }}</label>
                        <select class="form-control select2 {{ $errors->has('jenjang') ? 'is-invalid' : '' }}" name="jenjang" id="jenjang" required>
                            @foreach($jenjang as $id => $entry)
                                <option value="{{ $id }}" {{ request('jenjang') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('jenjang'))
                            <span class="text-danger">{{ $errors->first('jenjang') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.buku.fields.jenjang_helper') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="required">{{ trans('cruds.buku.fields.semester') }}</label>
                        <select class="form-control {{ $errors->has('semester') ? 'is-invalid' : '' }}" name="semester" id="semester" required>
                            <option value disabled {{ old('semester', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Models\Product::SEMESTER_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ request('semester') == (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('semester'))
                            <span class="text-danger">{{ $errors->first('semester') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.buku.fields.semester_helper') }}</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Filter</a>
                    </div>
                </div>
            </div>
        </form>
        <hr style="margin: 2em -15px;border-color:#ccc" />
        <form method="POST" action="{{ route("admin.stock-adjustments.store") }}" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="custom_price" value="{{ request('custom_price') }}">
            <input type="hidden" name="cover" value="{{ request('cover') }}">
            <input type="hidden" name="isi" value="{{ request('isi') }}">
            <input type="hidden" name="jenjang" value="{{ request('jenjang') }}">
            <input type="hidden" name="semester_buku" value="{{ request('semester_buku') }}">

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="date">{{ trans('cruds.stockAdjustment.fields.date') }}</label>
                        <input class="form-control date {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="date" id="date" value="{{ old('date') }}" required>
                        @if($errors->has('date'))
                            <span class="text-danger">{{ $errors->first('date') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.stockAdjustment.fields.date_helper') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="required">{{ trans('cruds.stockAdjustment.fields.operation') }}</label>
                        <select class="form-control {{ $errors->has('operation') ? 'is-invalid' : '' }}" name="operation" id="operation" required>
                            <option value disabled {{ old('operation', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Models\StockAdjustment::OPERATION_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('operation', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('operation'))
                            <span class="text-danger">{{ $errors->first('operation') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.stockAdjustment.fields.operation_helper') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="product_id">{{ trans('cruds.stockAdjustment.fields.product') }}</label>
                        <select class="form-control select2 {{ $errors->has('product') ? 'is-invalid' : '' }}" name="product_id" id="product_id" required>
                            @foreach($products as $id => $entry)
                                <option value="{{ $id }}" {{ old('product_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('product'))
                            <span class="text-danger">{{ $errors->first('product') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.stockAdjustment.fields.product_helper') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="quantity">{{ trans('cruds.stockAdjustment.fields.quantity') }}</label>
                        <input class="form-control {{ $errors->has('quantity') ? 'is-invalid' : '' }}" type="number" name="quantity" id="quantity" value="{{ old('quantity', '0') }}" min="1" step="1" required>
                        @if($errors->has('quantity'))
                            <span class="text-danger">{{ $errors->first('quantity') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.stockAdjustment.fields.quantity_helper') }}</span>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="note">{{ trans('cruds.stockAdjustment.fields.note') }}</label>
                        <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note" id="note">{{ old('note') }}</textarea>
                        @if($errors->has('note'))
                            <span class="text-danger">{{ $errors->first('note') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.stockAdjustment.fields.note_helper') }}</span>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <button class="btn btn-danger" type="submit">
                            {{ trans('global.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>



@endsection
