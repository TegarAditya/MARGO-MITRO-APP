@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.productionOrderDetail.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.production-order-details.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.productionOrderDetail.fields.production_order') }}
                        </th>
                        <td>
                            {{ $finishingOrderDetail->finishing_order->po_number ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productionOrderDetail.fields.product') }}
                        </th>
                        <td>
                            {{ $finishingOrderDetail->product->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productionOrderDetail.fields.order_qty') }}
                        </th>
                        <td>
                            {{ $finishingOrderDetail->order_qty }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productionOrderDetail.fields.prod_qty') }}
                        </th>
                        <td>
                            {{ $finishingOrderDetail->prod_qty }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productionOrderDetail.fields.ongkos_satuan') }}
                        </th>
                        <td>
                            {{ $finishingOrderDetail->ongkos_satuan }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productionOrderDetail.fields.ongkos_total') }}
                        </th>
                        <td>
                            {{ $finishingOrderDetail->ongkos_total }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.production-order-details.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection