<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pembelian</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        h1 { text-align: center; margin-bottom: 5px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 20px; }
        .badge-success { background: #28a745; color: white; padding: 2px 8px; border-radius: 4px; }
        .badge-warning { background: #ffc107; color: #333; padding: 2px 8px; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>Laporan Pembelian</h1>
    <p class="subtitle">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Tanggal</th>
                <th>Pabrik</th>
                <th class="text-right">Grand Total</th>
                <th class="text-right">Bayar</th>
                <th class="text-right">Hutang</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; $totalBayar = 0; @endphp
            @foreach($transaksis as $index => $transaksi)
                @php
                    $bayar = $transaksi->details->sum('total_bayar');
                    $hutang = $transaksi->grand_total - $bayar;
                    $grandTotal += $transaksi->grand_total;
                    $totalBayar += $bayar;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $transaksi->tanggal_transaksi->format('d/m/Y') }}</td>
                    <td>{{ $transaksi->factory->name ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($transaksi->grand_total, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($bayar, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($hutang, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $transaksi->status_lunas ? 'Lunas' : 'Belum Lunas' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">TOTAL</th>
                <th class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
                <th class="text-right">Rp {{ number_format($totalBayar, 0, ',', '.') }}</th>
                <th class="text-right">Rp {{ number_format($grandTotal - $totalBayar, 0, ',', '.') }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
