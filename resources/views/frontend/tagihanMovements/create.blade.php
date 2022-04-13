@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.tagihanMovement.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.tagihan-movements.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="reference">{{ trans('cruds.tagihanMovement.fields.reference') }}</label>
                            <input class="form-control" type="text" name="reference" id="reference" value="{{ old('reference', '') }}" required>
                            @if($errors->has('reference'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('reference') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.tagihanMovement.fields.reference_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.tagihanMovement.fields.type') }}</label>
                            <select class="form-control" name="type" id="type">
                                <option value disabled {{ old('type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\TagihanMovement::TYPE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('type', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.tagihanMovement.fields.type_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="nominal">{{ trans('cruds.tagihanMovement.fields.nominal') }}</label>
                            <input class="form-control" type="number" name="nominal" id="nominal" value="{{ old('nominal', '0') }}" step="0.01" required>
                            @if($errors->has('nominal'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nominal') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection