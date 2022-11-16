@extends('layouts.admin')
@section('content')
@include('admin.preorders.edit',['preorder' => new \App\Models\Preorder])
@endsection
