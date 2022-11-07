@extends('layouts.admin')
@section('content')
@include('admin.productionpeople.edit',[
    'productionperson' => new \App\Models\ProductionPerson,
    'login' => new \App\Models\User,
])
@endsection