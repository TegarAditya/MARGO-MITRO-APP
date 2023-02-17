@extends('layouts.admin')
@section('content')
@include('admin.purchases.edit',['purchase' => new \App\Models\Purchase])
@endsection
