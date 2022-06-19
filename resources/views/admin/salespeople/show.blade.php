@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.salesperson.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.salespeople.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.salesperson.fields.code') }}
                        </th>
                        <td>
                            {{ $salesperson->code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.salesperson.fields.name') }}
                        </th>
                        <td>
                            {{ $salesperson->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.salesperson.fields.area_pemasaran') }}
                        </th>
                        <td>
                            @foreach($salesperson->area_pemasarans as $key => $area_pemasaran)
                                <span class="label label-info">{{ $area_pemasaran->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.salesperson.fields.telephone') }}
                        </th>
                        <td>
                            {{ $salesperson->telephone }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.salesperson.fields.company') }}
                        </th>
                        <td>
                            {{ $salesperson->company }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.salesperson.fields.alamat') }}
                        </th>
                        <td>
                            {{ $salesperson->alamat }}
                        </td>
                    </tr>
                    {{-- <tr>
                        <th>
                            {{ trans('cruds.salesperson.fields.foto') }}
                        </th>
                        <td>
                            @if($salesperson->foto)
                                <a href="{{ $salesperson->foto->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $salesperson->foto->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr> --}}
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.salespeople.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
