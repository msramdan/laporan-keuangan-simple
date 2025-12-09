<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota Pabrik</title>
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
        .nota-table td { text-align: left; vertical-align: top; }
        .text-center { text-align: center !important; }
        
        /* Banyaknya cell */
        .banyaknya-cell { text-align: center; }
        .banyaknya-main { font-weight: bold; font-size: 14px; }
        .banyaknya-detail { font-size: 10px; color: #666; margin-top: 4px; }
        
        /* Footer */
        .grand-total-row { font-weight: bold; background-color: #e0e0e0; font-size: 14px; }
        
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
                    <td>: Pabrik</td>
                </tr>
                <tr>
                    <td class="label">Kepada</td>
                    <td>: {{ $clientName ?? 'Perusahaan' }}</td>
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
                    <th class="text-center">Banyaknya</th>
                    <th class="text-center">Harga Pabrik</th>
                    <th class="text-center">Jumlah Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($transaksis as $index => $t)
                    @php 
                        $banyaknya = $t->banyak_tsg - $t->banyak_tsg_tertolak;
                        $jumlahPembayaran = $banyaknya * $t->harga_pabrik;
                        $grandTotal += $jumlahPembayaran;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $t->mesin->name ?? '-' }}</td>
                        <td>{{ $t->nama_produk }}</td>
                        <td class="banyaknya-cell">
                            <div class="banyaknya-main">{{ format_angka($banyaknya) }}</div>
                            <div class="banyaknya-detail">
                                TSG Masuk: {{ format_angka($t->banyak_tsg) }}<br>
                                Reject: {{ format_angka($t->banyak_tsg_tertolak) }}
                            </div>
                        </td>
                        <td class="text-center">Rp {{ number_format($t->harga_pabrik, 0, ',', '.') }}</td>
                        <td class="text-center">Rp {{ number_format($jumlahPembayaran, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="grand-total-row">
                    <th colspan="5" class="text-center">TOTAL</th>
                    <th class="text-center">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
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
