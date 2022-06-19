@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.productionperson.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.productionpeople.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.productionperson.fields.code') }}
                        </th>
                        <td>
                            {{ $productionperson->code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productionperson.fields.name') }}
                        </th>
                        <td>
                            {{ $productionperson->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productionperson.fields.type') }}
                        </th>
                        <td>
                            {{ App\Models\Productionperson::TYPE_SELECT[$productionperson->type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productionperson.fields.contact') }}
                        </th>
                        <td>
                            {{ $productionperson->contact }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productionperson.fields.alamat') }}
                        </th>
                        <td>
                            {{ $productionperson->alamat }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productionperson.fields.company') }}
                        </th>
                        <td>
                            {{ $productionperson->company }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.productionpeople.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
