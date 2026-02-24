<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>KM Report</title>
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12px;
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
    <div class="title">Mileage Report</div>
    <div class="meta"><strong>From:</strong> {{ $startDate }}</div>
    <div class="meta"><strong>To:</strong> {{ $endDate }}</div>
    <div class="meta">
      <strong>Car:</strong>
      {{ $selectedCar ? ($selectedCar->name) : 'All Cars' }}
    </div>
    <div class="meta"><strong>Total Mileage:</strong> {{ number_format($totalMileage, 2) }} KM</div>
    <div class="meta"><strong>Average (per logged day):</strong> {{ number_format($avgMileage, 2) }} KM</div>
    <div class="meta"><strong>Monthly Average ({{ $periodMonthsCount }} month{{ $periodMonthsCount > 1 ? 's' : '' }}):</strong> {{ number_format($avgMonthlyMileage, 2) }} KM</div>
  </div>

  <table>
    <thead>
      <tr>
        <th>Date</th>
        <th class="num">Mileage (KM)</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rows as $row)
        <tr>
          <td>{{ $row['date'] }}</td>
          <td class="num">{{ number_format($row['mileage'], 2) }}</td>
        </tr>
      @endforeach
      <tr>
        <td><strong>Total</strong></td>
        <td class="num"><strong>{{ number_format($totalMileage, 2) }}</strong></td>
      </tr>
      <tr>
        <td><strong>Average ({{ $loggedDays }} logged days)</strong></td>
        <td class="num"><strong>{{ number_format($avgMileage, 2) }}</strong></td>
      </tr>
      <tr>
        <td><strong>Monthly Average ({{ $periodMonthsCount }} month{{ $periodMonthsCount > 1 ? 's' : '' }})</strong></td>
        <td class="num"><strong>{{ number_format($avgMonthlyMileage, 2) }}</strong></td>
      </tr>
    </tbody>
  </table>
</body>
</html>
