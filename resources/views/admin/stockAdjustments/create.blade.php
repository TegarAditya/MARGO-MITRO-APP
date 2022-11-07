@extends('layouts.admin')
@section('content')
@include('admin.stockAdjustments.edit',['stockAdjustment' => new \App\Models\StockAdjustment])
@endsection
