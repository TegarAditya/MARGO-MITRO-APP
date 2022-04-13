@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.tagihanMovement.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.tagihan-movements.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.tagihanMovement.fields.reference') }}
                        </th>
                        <td>
                            {{ $tagihanMovement->reference }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tagihanMovement.fields.type') }}
                        </th>
                        <td>
                            {{ App\Models\TagihanMovement::TYPE_SELECT[$tagihanMovement->type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tagihanMovement.fields.nominal') }}
                        </th>
                        <td>
                            {{ $tagihanMovement->nominal }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.tagihan-movements.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection