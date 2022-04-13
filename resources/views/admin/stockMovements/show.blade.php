@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.stockMovement.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.stock-movements.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.stockMovement.fields.reference') }}
                        </th>
                        <td>
                            {{ $stockMovement->reference }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.stockMovement.fields.type') }}
                        </th>
                        <td>
                            {{ App\Models\StockMovement::TYPE_SELECT[$stockMovement->type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.stockMovement.fields.product') }}
                        </th>
                        <td>
                            {{ $stockMovement->product->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.stockMovement.fields.quantity') }}
                        </th>
                        <td>
                            {{ $stockMovement->quantity }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.stockMovement.fields.created_at') }}
                        </th>
                        <td>
                            {{ $stockMovement->created_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.stock-movements.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection