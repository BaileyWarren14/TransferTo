<!-- resources/views/admin/dashboard.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Admin Dashboard</h1>
    <p>Bienvenido, {{ auth()->guard('admin')->user()->name }}</p>
@endsection
