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
            <div class="row mb-3">
                <div class="col-2"><h5>Nama Sales :</h5></div>
                <div class="col-4"><h5><strong>{{ $customPrice->sales->name }}</strong></h5></div>
            </div>
            <table class="table table-bordered table-striped">
                <thead>
                    <th>Nama</th>
                    <th>Halaman</th>
                    <th>Harga</th>
                </thead>
                <tbody>
                    @foreach ($harga as $element)
                        <tr>
                            <td class="text-center">
                                {{ $element->nama }}
                            </td>
                            <td  class="text-center">
                                Halaman {{ $element->kategori->name }}
                            </td>
                            <td  class="text-right">
                                @money($element->harga)
                            </td>
                        </tr>
                    @endforeach
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
