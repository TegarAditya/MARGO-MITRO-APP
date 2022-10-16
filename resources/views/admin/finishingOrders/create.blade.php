@extends('layouts.admin')
@section('content')
@include('admin.finishingOrders.edit',['finishingOrder' => new \App\Models\FinishingOrder])
@endsection
