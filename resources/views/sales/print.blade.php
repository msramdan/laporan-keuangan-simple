<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembeli - {{ $sale->buyer_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
        }
        .details {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-end {
            text-align: right;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>NOTA PEMBELI</h2>
        <p>{{ config('app.name', 'Laporan Keuangan') }}</p>
    </div>

    <div class="details">
        <table>
            <tr>
                <td style="border: none; width: 150px;"><strong>Nama Pembeli</strong></td>
                <td style="border: none;">: {{ $sale->buyer_name }}</td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Tanggal</strong></td>
                <td style="border: none;">: {{ format_date($sale->date, 'd F Y') }}</td>
            </tr>
            <tr>
                <td style="border: none;"><strong>No. Transaksi</strong></td>
                <td style="border: none;">: #{{ $sale->id }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Asal Pabrik</th>
                <th class="text-end">Berat (Kg)</th>
                <th class="text-end">Harga Jual</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->purchaseItem->product->name }}</td>
                    <td>{{ $item->purchaseItem->purchase->factory->name }}</td>
                    <td class="text-end">{{ number_format($item->purchaseItem->weight, 2) }}</td>
                    <td class="text-end">{{ format_rupiah($item->selling_price) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-end">Grand Total</th>
                <th class="text-end">{{ format_rupiah($sale->grand_total) }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Hormat Kami,</p>
        <br><br><br>
        <p>( Admin )</p>
    </div>
</body>
</html>
