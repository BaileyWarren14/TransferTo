@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Select a user to chat</h2>

    <div class="row">
        <!-- Drivers -->
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Drivers</h4>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($drivers as $driver)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $driver->name }} {{ $driver->lastname }}</span>
                            <a href="{{ route('messages.chat', ['type' => 'driver', 'id' => $driver->id]) }}" class="btn btn-sm btn-outline-primary">
                                Chat
                            </a>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No drivers available</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Admins -->
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Admins</h4>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($admins as $admin)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $admin->name }} {{ $admin->lastname }}</span>
                            <a href="{{ route('messages.chat', ['type' => 'admin', 'id' => $admin->id]) }}" class="btn btn-sm btn-outline-success">
                                Chat
                            </a>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No administrators available </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
