@extends('layouts.admin')
@section('content')
@include('admin.invoices.edit',['invoice' => new \App\Models\Invoice])
@endsection
