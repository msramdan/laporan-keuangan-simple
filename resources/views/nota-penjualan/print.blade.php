<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota Penjualan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; padding: 20px; }
        .nota-container { max-width: 800px; margin: 0 auto; }
        
        /* Header */
        .nota-title { text-align: center; font-size: 24px; font-weight: bold; margin-bottom: 20px; text-decoration: underline; }
        .nota-header { margin-bottom: 20px; }
        .nota-header table { width: 100%; }
        .nota-header td { padding: 3px 0; vertical-align: top; }
        .nota-header .label { width: 80px; font-weight: bold; }
        
        /* Table */
        .nota-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .nota-table th, .nota-table td { border: 1px solid #333; padding: 8px; }
        .nota-table th { background-color: #f0f0f0; font-weight: bold; text-align: center; }
        .nota-table td { text-align: left; }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        
        /* Footer */
        .nota-footer { margin-top: 30px; }
        .total-row { font-weight: bold; background-color: #f5f5f5; }
        .grand-total-row { font-weight: bold; background-color: #e0e0e0; font-size: 14px; }
        
        /* Signature */
        .signature-section { margin-top: 50px; display: flex; justify-content: space-between; }
        .signature-box { width: 200px; text-align: center; }
        .signature-line { border-top: 1px solid #333; margin-top: 60px; padding-top: 5px; }
        
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
                    <td>: Perusahaan</td>
                </tr>
                <tr>
                    <td class="label">Kepada</td>
                    <td>: {{ $clientName ?? 'Client' }}</td>
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
                    <th>Nama Mesin</th>
                    <th>Nama Produk</th>
                    <th class="text-right">Banyak TSG (Kg)</th>
                    <th class="text-right">TSG Tertolak (Kg)</th>
                    <th class="text-right">Harga Jual</th>
                    <th class="text-right">Grand Total</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($transaksis as $index => $t)
                    @php 
                        $tsgBersih = $t->banyak_tsg - $t->banyak_tsg_tertolak;
                        $itemTotal = $tsgBersih * $t->harga_jual;
                        $grandTotal += $itemTotal;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $t->mesin->name ?? '-' }}</td>
                        <td>{{ $t->nama_produk }}</td>
                        <td class="text-right">{{ number_format($t->banyak_tsg, 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($t->banyak_tsg_tertolak, 2, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($t->harga_jual, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($itemTotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="grand-total-row">
                    <th colspan="6" class="text-right">TOTAL</th>
                    <th class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

        <!-- Signature Section -->
        <table style="width: 100%; margin-top: 50px; border: none;">
            <tr>
                <td style="width: 50%; text-align: center; border: none; vertical-align: top;">
                    <p>Penerima,</p>
                    <div style="margin-top: 60px; border-top: 1px solid #333; width: 180px; margin-left: auto; margin-right: auto; padding-top: 5px;">
                        ( ............................ )
                    </div>
                </td>
                <td style="width: 50%; text-align: center; border: none; vertical-align: top;">
                    <p>Pengirim,</p>
                    <div style="margin-top: 60px; border-top: 1px solid #333; width: 180px; margin-left: auto; margin-right: auto; padding-top: 5px;">
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
