<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota Pembelian</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; padding: 20px; }
        .nota-container { max-width: 800px; margin: 0 auto; }
        
        /* Header */
        .nota-title { text-align: center; font-size: 24px; font-weight: bold; margin-bottom: 20px; text-decoration: underline; }
        .nota-header { margin-bottom: 20px; }
        .nota-header table { width: 100%; border: none; }
        .nota-header td { padding: 3px 0; vertical-align: top; border: none; }
        .nota-header .label { width: 80px; font-weight: bold; }
        
        /* Table */
        .nota-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .nota-table th, .nota-table td { border: 1px solid #333; padding: 8px; }
        .nota-table th { background-color: #f0f0f0; font-weight: bold; text-align: center; }
        .nota-table td { text-align: left; }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        
        /* Footer */
        .grand-total-row { font-weight: bold; background-color: #e0e0e0; font-size: 14px; }
        
        /* Signature */
        .signature-table { width: 100%; margin-top: 50px; border: none; }
        .signature-table td { width: 50%; text-align: center; border: none; vertical-align: top; }
        .signature-line { margin-top: 60px; border-top: 1px solid #333; width: 180px; margin-left: auto; margin-right: auto; padding-top: 5px; }
        
        @media print {
            body { padding: 10px; }
            .nota-container { max-width: 100%; }
        }
    </style>
</head>
<body>
    <div class="nota-container">
        <!-- Title -->
        <h1 class="nota-title">NOTA</h1>
        
        <!-- Header Info -->
        <div class="nota-header">
            <table>
                <tr>
                    <td class="label">Dari</td>
                    <td>: {{ $factoryName ?? 'Pabrik' }}</td>
                </tr>
                <tr>
                    <td class="label">Kepada</td>
                    <td>: Perusahaan</td>
                </tr>
                <tr>
                    <td class="label">Tanggal</td>
                    <td>: {{ now()->format('d/m/Y') }}</td>
                </tr>
            </table>
        </div>

        <!-- Transaction Table -->
        <table class="nota-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 40px;">No</th>
                    <th>Nama Paket</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Harga/Unit</th>
                    <th class="text-right">Grand Total</th>
                    <th class="text-right">Dibayar</th>
                    <th class="text-right">Sisa</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $grandTotal = 0; 
                    $totalBayar = 0; 
                    $no = 1;
                @endphp
                @foreach($transaksis as $transaksi)
                    @foreach($transaksi->details as $detail)
                        @php 
                            $itemTotal = $detail->qty * $detail->harga_per_unit;
                            $sisa = $itemTotal - $detail->total_bayar;
                            $grandTotal += $itemTotal;
                            $totalBayar += $detail->total_bayar;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $detail->paket->name ?? '-' }}</td>
                            <td class="text-right">{{ $detail->qty }}</td>
                            <td class="text-right">Rp {{ number_format($detail->harga_per_unit, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($itemTotal, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($detail->total_bayar, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($sisa, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
            <tfoot>
                <tr class="grand-total-row">
                    <th colspan="4" class="text-right">TOTAL</th>
                    <th class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
                    <th class="text-right">Rp {{ number_format($totalBayar, 0, ',', '.') }}</th>
                    <th class="text-right">Rp {{ number_format($grandTotal - $totalBayar, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

        <!-- Signature Section -->
        <table class="signature-table">
            <tr>
                <td>
                    <p>Penerima,</p>
                    <div class="signature-line">
                        ( ............................ )
                    </div>
                </td>
                <td>
                    <p>Pengirim,</p>
                    <div class="signature-line">
                        ( ............................ )
                    </div>
                </td>
            </tr>
        </table>

        <p style="margin-top: 30px; font-size: 10px; color: #666; text-align: center;">
            Dicetak pada: {{ now()->format('d/m/Y H:i') }}
        </p>
    </div>
</body>
</html>
