@extends('layouts.admin')
@section('content')

<form method="POST" action="{{ !$productionperson->id ? route('admin.productionpeople.store') : route("admin.productionpeople.update", [$productionperson->id]) }}" enctype="multipart/form-data">
    @method(!$productionperson->id ? 'POST' : 'PUT')
    @csrf

    <div class="card">
        <div class="row">
            <div class="col-12 col-lg-6 mb-4">
                <div class="card-header">
                    <strong>{{ trans('global.edit') }} {{ trans('cruds.productionperson.title_singular') }}</strong>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('cruds.productionperson.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $productionperson->name) }}" required>
                        @if($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.productionperson.fields.name_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="required">{{ trans('cruds.productionperson.fields.type') }}</label>
                        <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type" required>
                            <option value disabled {{ old('type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Models\Productionperson::TYPE_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('type', $productionperson->type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('type'))
                            <span class="text-danger">{{ $errors->first('type') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.productionperson.fields.type_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="contact">{{ trans('cruds.productionperson.fields.contact') }}</label>
                        <input class="form-control {{ $errors->has('contact') ? 'is-invalid' : '' }}" type="text" name="contact" id="contact" value="{{ old('contact', $productionperson->contact) }}">
                        @if($errors->has('contact'))
                            <span class="text-danger">{{ $errors->first('contact') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.productionperson.fields.contact_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="alamat">{{ trans('cruds.productionperson.fields.alamat') }}</label>
                        <textarea class="form-control {{ $errors->has('alamat') ? 'is-invalid' : '' }}" name="alamat" id="alamat">{{ old('alamat', $productionperson->alamat) }}</textarea>
                        @if($errors->has('alamat'))
                            <span class="text-danger">{{ $errors->first('alamat') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.productionperson.fields.alamat_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="company">{{ trans('cruds.productionperson.fields.company') }}</label>
                        <input class="form-control {{ $errors->has('company') ? 'is-invalid' : '' }}" type="text" name="company" id="company" value="{{ old('company', $productionperson->company) }}">
                        @if($errors->has('company'))
                            <span class="text-danger">{{ $errors->first('company') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.productionperson.fields.company_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-danger" type="submit">
                            {{ trans('global.save') }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 mb-4 border-left">
                <div class="card-header">
                    <strong>Akun Login</strong> <span class="text-muted">(Opsional)</span>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Email</label>
                        <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="text" name="email" id="email" value="{{ old('email', $login->email) }}" placeholder="{{ $login->email }}">
                        @if($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="name">Password</label>
                        <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password" value="{{ old('password') }}" autocomplete="new-password">
                        @if($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif

                        @if ($login->id)
                            <span class="text-sm text-muted">Biarkan kosong jika tidak mengubah password</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <button class="btn btn-dark" type="submit">
                            Simpan Akun
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
