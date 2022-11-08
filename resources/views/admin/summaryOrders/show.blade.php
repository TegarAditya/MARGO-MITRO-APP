@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.summaryOrder.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.summary-orders.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.summaryOrder.fields.preorder') }}
                        </th>
                        <td>
                            {{ $summaryOrder->preorder->no_preorder ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.summaryOrder.fields.order') }}
                        </th>
                        <td>
                            {{ $summaryOrder->order->no_order ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.summaryOrder.fields.product') }}
                        </th>
                        <td>
                            {{ $summaryOrder->product->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.summaryOrder.fields.type') }}
                        </th>
                        <td>
                            {{ App\Models\SummaryOrder::TYPE_SELECT[$summaryOrder->type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.summaryOrder.fields.category') }}
                        </th>
                        <td>
                            {{ $summaryOrder->category->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.summaryOrder.fields.quantity') }}
                        </th>
                        <td>
                            {{ $summaryOrder->quantity }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.summary-orders.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection