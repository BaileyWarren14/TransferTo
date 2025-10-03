@extends('layouts.app_admin')

@section('content')
    <h2>Driver Reports</h2>
    <table class="table-auto w-full">
        <thead>
            <tr>
                <th>Driver</th>
                <th>Type</th>
                <th>Message</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($alerts as $alert)
                <tr>
                    <td>{{ $alert->driver->name }}</td>
                    <td>{{ $alert->type }}</td>
                    <td>{{ $alert->message }}</td>
                    <td>{{ $alert->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection