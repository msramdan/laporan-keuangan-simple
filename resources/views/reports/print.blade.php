<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #333;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            font-weight: bold;
            background-color: #e6e6e6;
        }
        .factory-header {
            background-color: #d9d9d9;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KEUANGAN</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
    </div>

    @foreach ($groupedPurchases as $factoryId => $purchases)
        @php
            $factoryName = $purchases->first()->factory->name;
        @endphp
        <h3>Pabrik: {{ $factoryName }}</h3>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Detail Produk</th>
                    <th class="text-right">Total Harga</th>
                    <th class="text-right">Hutang</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchases as $purchase)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($purchase->date)->format('d/m/Y') }}</td>
                        <td>
                            <ul style="margin: 0; padding-left: 15px;">
                                @foreach ($purchase->items as $item)
                                    <li>{{ $item->product->name }} ({{ $item->weight }} {{ $item->product->unit ?? 'kg' }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="text-right">Rp {{ number_format($purchase->grand_total, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($purchase->total_debt, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $purchase->is_paid ? 'Lunas' : 'Belum Lunas' }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="2" class="text-right">Subtotal {{ $factoryName }}</td>
                    <td class="text-right">Rp {{ number_format($purchases->sum('grand_total'), 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($purchases->sum('total_debt'), 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    @endforeach

    <h3>Ringkasan Total</h3>
    <table>
        <thead>
            <tr>
                <th>Pabrik</th>
                <th class="text-right">Total Pembelian</th>
                <th class="text-right">Total Hutang</th>
                <th class="text-right">Total Penjualan</th>
                <th class="text-right">Laba Rugi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($summary as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td class="text-right">Rp {{ number_format($item['total_purchase'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item['total_debt'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item['total_sale'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item['profit'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td>GRAND TOTAL</td>
                <td class="text-right">Rp {{ number_format($grandTotalPurchase, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($grandTotalDebt, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($grandTotalSale, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($grandTotalProfit, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
