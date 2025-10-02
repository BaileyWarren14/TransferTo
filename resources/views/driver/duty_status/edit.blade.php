@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4>Edit Duty Status Log</h4>

    <form action="{{ route('driver.duty_status.update', $log->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                @foreach(['OFF','SB','D','ON','WT','PC','YM'] as $state)
                    <option value="{{ $state }}" {{ $log->status == $state ? 'selected' : '' }}>
                        {{ $state }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="changed_at" class="form-label">Changed At</label>
            <input type="datetime-local" name="changed_at" id="changed_at" class="form-control" 
                   value="{{ $log->changed_at }}" required>
        </div>

        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" name="location" id="location" class="form-control" value="{{ $log->location }}">
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" id="notes" class="form-control">{{ $log->notes }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="{{ route('driver.logs.today') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
