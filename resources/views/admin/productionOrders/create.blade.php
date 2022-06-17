@extends('layouts.admin')
@section('content')
@include('admin.productionOrders.edit',['productionOrder' => new \App\Models\ProductionOrder])
@endsection
