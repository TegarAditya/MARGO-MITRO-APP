@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.tagihanMovement.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.tagihan-movements.update", [$tagihanMovement->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="reference">{{ trans('cruds.tagihanMovement.fields.reference') }}</label>
                <input class="form-control {{ $errors->has('reference') ? 'is-invalid' : '' }}" type="text" name="reference" id="reference" value="{{ old('reference', $tagihanMovement->reference) }}" required>
                @if($errors->has('reference'))
                    <span class="text-danger">{{ $errors->first('reference') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.tagihanMovement.fields.reference_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.tagihanMovement.fields.type') }}</label>
                <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type">
                    <option value disabled {{ old('type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\TagihanMovement::TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('type', $tagihanMovement->type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('type'))
                    <span class="text-danger">{{ $errors->first('type') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.tagihanMovement.fields.type_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="nominal">{{ trans('cruds.tagihanMovement.fields.nominal') }}</label>
                <input class="form-control {{ $errors->has('nominal') ? 'is-invalid' : '' }}" type="number" name="nominal" id="nominal" value="{{ old('nominal', $tagihanMovement->nominal) }}" step="0.01" required>
                @if($errors->has('nominal'))
                    <span class="text-danger">{{ $errors->first('nominal') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.tagihanMovement.fields.nominal_helper') }}</span>
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