@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.pembayaran.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.pembayarans.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pembayaran.fields.no_kwitansi') }}
                                    </th>
                                    <td>
                                        {{ $pembayaran->no_kwitansi }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pembayaran.fields.tagihan') }}
                                    </th>
                                    <td>
                                        {{ $pembayaran->tagihan->saldo ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pembayaran.fields.nominal') }}
                                    </th>
                                    <td>
                                        {{ $pembayaran->nominal }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pembayaran.fields.diskon') }}
                                    </th>
                                    <td>
                                        {{ $pembayaran->diskon }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pembayaran.fields.bayar') }}
                                    </th>
                                    <td>
                                        {{ $pembayaran->bayar }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pembayaran.fields.tanggal') }}
                                    </th>
                                    <td>
                                        {{ $pembayaran->tanggal }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.pembayarans.index') }}">
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