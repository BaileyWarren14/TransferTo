<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        .page-break { page-break-after: always; }
        .header { display: flex; justify-content: space-between; align-items: center; }
        .title { text-align: center; font-weight: bold; font-size: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        .section { margin-bottom: 15px; }
    </style>
</head>
<body>

@foreach ($daysData as $day)
    <div class="header">
        <div><strong>Transfer-To</strong></div>
        <div class="title">
            DRIVER'S DAILY LOG<br>
            <span style="font-size: 12px;">TEXAS OIL AND GAS 70 HOUR / 7 DAY</span>
        </div>
        <div><strong>{{ $day['date'] }}</strong></div>
    </div>

    <div class="section">
        <strong>Driver:</strong> {{ strtoupper($driver->last_name) }}, {{ ucfirst($driver->first_name) }}
        <span style="float: right;"><strong>Co-Drivers:</strong> _____________</span><br>
        <strong>Fleet ID:</strong> ____________ <br>
        <strong>Distance:</strong> {{ $day['distance'] }} <br>
        <strong>Vehicles:</strong> {{ $day['plate'] }}
        <span style="float: right;"><strong>Trailers:</strong> {{ $day['trailer'] }}</span><br>
        <strong>Carrier:</strong> ____________ <br>
        <strong>Main Office:</strong> ____________ <br>
        <strong>Home Terminal:</strong> ____________
        <span style="float: right;"><strong>Shipping Docs:</strong> ____________</span>
    </div>

    {{-- Aquí puedes insertar tu gráfico electrónico (SVG o imagen generada) --}}
    <div class="section">
        <h4>Electronic Logbook</h4>
        <img src="{{ $day['graph'] }}" alt="Daily Graph" width="100%">
    </div>

    <h4>Duty Status Logs</h4>
    <table>
        <thead>
            <tr>
                <Th></Th>
                <th>Status</th>
                <th>Time</th>
                <th>Location</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($day['logs'] as $log)
                <tr>
                    <td>{{ $log->status }}</td>
                    <td>{{ \Carbon\Carbon::parse($log->changed_at)->format('h:i A') }}</td>
                    <td>{{ $log->location ?? '-' }}</td>
                    <td>{{ $log->notes ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if (!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach

</body>
</html>
