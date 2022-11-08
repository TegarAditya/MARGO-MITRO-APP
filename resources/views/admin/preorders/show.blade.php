@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.preorder.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.preorders.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.preorder.fields.no_preorder') }}
                        </th>
                        <td>
                            {{ $preorder->no_preorder }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.preorder.fields.date') }}
                        </th>
                        <td>
                            {{ $preorder->date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.preorder.fields.note') }}
                        </th>
                        <td>
                            {{ $preorder->note }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.preorders.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection