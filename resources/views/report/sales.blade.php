<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h2 {
            text-align: center;
            background: #007bff;
            color: white;
            padding: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th {
            background: green;
            color: white;
            padding: 8px;
            border: 1px solid #000;
        }
        table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        .total-row {
            background: #dff0d8;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Laporan Penjualan ({{ $date }})</h2>

    <table>
        <thead>
            <tr>
                <th></th>
                <th>Barang</th>
                <th>Harga Barang</th>
                <th>Jumlah</th>
                <th>Rata" diskon</th>
                <th>Total</th>
                <th>- Pengembalian</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $i => $report)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $report->product }}</td>
                    <td>Rp. {{ number_format($report->unit_price, 0) }}</td>
                    <td>{{ $report->quantity }}</td>
                    <td>Rp. {{ number_format($report->average_discount, 0) }}</td>
                    <td>Rp. {{ number_format($report->total, 0) }}</td>
                    <td> {{ $report->total_return }} </td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td>Diskon Total</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>Rp. {{$discountTotal}}</td>
                <td>-</td>
            </tr>
            <tr class="total-row">
                <td colspan="5">Total</td>
                <td>{{ $reports->sum('quantity') }}</td>
                <td>Rp. {{ number_format($reports->sum('total') - $discountTotal, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
