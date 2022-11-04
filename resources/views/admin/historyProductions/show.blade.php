@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.historyProduction.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.history-productions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.historyProduction.fields.reference') }}
                        </th>
                        <td>
                            {{ $historyProduction->reference->no_preorder ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.historyProduction.fields.type') }}
                        </th>
                        <td>
                            {{ App\Models\HistoryProduction::TYPE_SELECT[$historyProduction->type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.historyProduction.fields.summary_order') }}
                        </th>
                        <td>
                            {{ $historyProduction->summary_order->type ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.historyProduction.fields.product') }}
                        </th>
                        <td>
                            {{ $historyProduction->product->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.historyProduction.fields.quantity') }}
                        </th>
                        <td>
                            {{ $historyProduction->quantity }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.history-productions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection