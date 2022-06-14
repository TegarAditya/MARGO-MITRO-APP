@extends('layouts.admin')
@section('content')
@include('admin.pembayarans.edit',['invoice' => new \App\Models\Pembayaran])
@endsection