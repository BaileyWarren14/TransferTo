@extends('layouts.app')

@section('content')
<h1>Admin Dashboard</h1>
<p>Welcome, {{ auth()->guard('admin')->user()->name }}</p>
@endsection