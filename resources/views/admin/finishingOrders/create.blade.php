@extends('layouts.admin')

@section('content')
    @include('admin.finishingOrders.edit', [
        'finishingOrder' => !isset($finishingOrder) ? new \App\Models\FinishingOrder : $finishingOrder,
    ])
@endsection
