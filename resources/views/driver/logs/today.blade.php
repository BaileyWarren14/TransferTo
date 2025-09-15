@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">Today's Duty Status Log</h2>

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    @if($logs->isEmpty())
        <p class="text-center">No duty status changes recorded today.</p>
    @else
        <div class="list-group">
            @foreach($logs as $log)
                <div class="list-group-item mb-2">
                    <strong>Time:</strong> {{ \Carbon\Carbon::parse($log->changed_at)->format('H:i') }} <br>
                    <strong>Status:</strong> {{ $log->status }} <br>
                    <strong>Location:</strong> {{ $log->location }} <br>
                    <strong>Notes:</strong> {{ $log->notes ?? '-' }}
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
