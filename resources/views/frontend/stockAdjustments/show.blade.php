@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.stockAdjustment.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.stock-adjustments.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.stockAdjustment.fields.date') }}
                                    </th>
                                    <td>
                                        {{ $stockAdjustment->date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.stockAdjustment.fields.operation') }}
                                    </th>
                                    <td>
                                        {{ App\Models\StockAdjustment::OPERATION_SELECT[$stockAdjustment->operation] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.stockAdjustment.fields.product') }}
                                    </th>
                                    <td>
                                        {{ $stockAdjustment->product->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.stockAdjustment.fields.quantity') }}
                                    </th>
                                    <td>
                                        {{ $stockAdjustment->quantity }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.stockAdjustment.fields.note') }}
                                    </th>
                                    <td>
                                        {{ $stockAdjustment->note }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.stock-adjustments.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection