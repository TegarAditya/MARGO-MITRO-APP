@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.salesperson.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.salespeople.update", [$salesperson->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.salesperson.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $salesperson->name) }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.salesperson.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="area_pemasarans">{{ trans('cruds.salesperson.fields.area_pemasaran') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('area_pemasarans') ? 'is-invalid' : '' }}" name="area_pemasarans[]" id="area_pemasarans" multiple>
                    @foreach($area_pemasarans as $id => $area_pemasaran)
                        <option value="{{ $id }}" {{ (in_array($id, old('area_pemasarans', [])) || $salesperson->area_pemasarans->contains($id)) ? 'selected' : '' }}>{{ $area_pemasaran }}</option>
                    @endforeach
                </select>
                @if($errors->has('area_pemasarans'))
                    <span class="text-danger">{{ $errors->first('area_pemasarans') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.salesperson.fields.area_pemasaran_helper') }}</span>
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