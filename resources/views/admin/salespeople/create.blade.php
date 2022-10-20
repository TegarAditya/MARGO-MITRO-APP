@extends('layouts.admin')
@section('content')
@include('admin.salespeople.edit', ['salesperson' => new \App\Models\Salesperson])
@endsection
