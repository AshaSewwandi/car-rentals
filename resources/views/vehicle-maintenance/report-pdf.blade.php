<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Vehicle Maintenance Report</title>
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 11px;
      color: #1f2937;
      margin: 20px;
    }
    .header {
      margin-bottom: 14px;
    }
    .title {
      font-size: 18px;
      font-weight: 700;
      margin-bottom: 8px;
    }
    .meta {
      margin: 2px 0;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    th, td {
      border: 1px solid #cfd4dc;
      padding: 6px 8px;
      text-align: left;
      vertical-align: top;
    }
    th {
      background: #eef2f7;
      font-weight: 700;
    }
    td.num, th.num {
      text-align: right;
    }
  </style>
</head>
<body>
  <div class="header">
    <div class="title">Vehicle Maintenance Report</div>
    <div class="meta"><strong>Month:</strong> {{ $month ?: 'All months' }}</div>
    <div class="meta"><strong>Vehicle:</strong> {{ $selectedCar ? $selectedCar->name.' ('.$selectedCar->plate_no.')' : 'All vehicles' }}</div>
    <div class="meta"><strong>Total Amount:</strong> Rs {{ number_format($total, 2) }}</div>
    <div class="meta"><strong>Records:</strong> {{ $records->count() }}</div>
  </div>

  <table>
    <thead>
      <tr>
        <th>Date</th>
        <th>Vehicle</th>
        <th>Part / Work</th>
        <th>Mileage</th>
        <th class="num">Amount</th>
        <th>Note</th>
      </tr>
    </thead>
    <tbody>
      @forelse($records as $record)
        <tr>
          <td>{{ $record->service_date->format('Y-m-d') }}</td>
          <td>{{ $record->car?->name }}{{ $record->car?->plate_no ? ' ('.$record->car->plate_no.')' : '' }}</td>
          <td>{{ $record->part_name }}</td>
          <td>{{ $record->mileage !== null ? number_format($record->mileage).' km' : '-' }}</td>
          <td class="num">Rs {{ number_format((float) $record->amount, 2) }}</td>
          <td>{{ $record->note ?: '-' }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="6">No maintenance records found.</td>
        </tr>
      @endforelse
      <tr>
        <td colspan="4"><strong>Total</strong></td>
        <td class="num"><strong>Rs {{ number_format($total, 2) }}</strong></td>
        <td></td>
      </tr>
    </tbody>
  </table>
</body>
</html>
