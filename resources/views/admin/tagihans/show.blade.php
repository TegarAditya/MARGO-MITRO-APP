@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.tagihan.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.tagihans.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.tagihan.fields.order') }}
                        </th>
                        <td>
                            {{ $tagihan->order->no_order ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tagihan.fields.saldo') }}
                        </th>
                        <td>
                            {{ $tagihan->saldo }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tagihan.fields.salesperson') }}
                        </th>
                        <td>
                            {{ $tagihan->salesperson->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.tagihans.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection