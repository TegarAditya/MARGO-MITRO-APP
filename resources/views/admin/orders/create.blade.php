@extends('layouts.admin')
@section('content')
@include('admin.orders.edit',['order' => new \App\Models\Order])
@endsection