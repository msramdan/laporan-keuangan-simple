<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota Pabrik</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        h1 { text-align: center; margin-bottom: 5px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Nota Pabrik</h1>
    <p class="subtitle">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Tanggal</th>
                <th>Client</th>
                <th>Mesin</th>
                <th>Produk</th>
                <th class="text-right">Harga Pabrik</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($transaksis as $index => $t)
                @php $total += $t->harga_pabrik; @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $t->tanggal_transaksi->format('d/m/Y') }}</td>
                    <td>{{ $t->client->name ?? '-' }}</td>
                    <td>{{ $t->mesin->name ?? '-' }}</td>
                    <td>{{ $t->nama_produk }}</td>
                    <td class="text-right">Rp {{ number_format($t->harga_pabrik, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-right">TOTAL</th>
                <th class="text-right">Rp {{ number_format($total, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
