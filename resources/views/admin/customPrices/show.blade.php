@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.customPrice.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.custom-prices.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.customPrice.fields.nama') }}
                        </th>
                        <td>
                            {{ $customPrice->nama }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.customPrice.fields.kategori') }}
                        </th>
                        <td>
                            {{ $customPrice->kategori->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.customPrice.fields.harga') }}
                        </th>
                        <td>
                            {{ $customPrice->harga }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.custom-prices.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection