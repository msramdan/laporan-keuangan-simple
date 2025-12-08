<?php

namespace Database\Seeders;

use App\Models\Factory;
use App\Models\Paket;
use App\Models\Client;
use App\Models\Mesin;
use App\Models\Unit;
use App\Models\TransaksiPembelian;
use App\Models\TransaksiMesin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinancialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Units
        $units = ['Kg', 'Pcs', 'Ton', 'Liter'];
        foreach ($units as $unitName) {
            Unit::firstOrCreate(
                ['code' => strtoupper($unitName)],
                ['name' => $unitName]
            );
        }

        // 2. Factories (Pabrik)
        $factories = [
            ['code' => 'PB-001', 'name' => 'Pabrik Utama', 'keterangan' => 'Pabrik utama supplier'],
            ['code' => 'PB-002', 'name' => 'Pabrik Cabang A', 'keterangan' => 'Cabang di area A'],
            ['code' => 'PB-003', 'name' => 'Pabrik Cabang B', 'keterangan' => 'Cabang di area B'],
        ];
        foreach ($factories as $factory) {
            Factory::firstOrCreate(['code' => $factory['code']], $factory);
        }

        // 3. Pakets
        $pakets = [
            ['code' => 'PKT-001', 'name' => 'Paket Basic', 'keterangan' => 'Paket standar'],
            ['code' => 'PKT-002', 'name' => 'Paket Premium', 'keterangan' => 'Paket premium quality'],
            ['code' => 'PKT-003', 'name' => 'Paket Gold', 'keterangan' => 'Paket gold exclusive'],
        ];
        foreach ($pakets as $paket) {
            Paket::firstOrCreate(['code' => $paket['code']], $paket);
        }

        // 4. Clients
        $clients = [
            ['code' => 'CL-001', 'name' => 'PT Maju Jaya', 'keterangan' => 'Client utama'],
            ['code' => 'CL-002', 'name' => 'CV Berkah Sentosa', 'keterangan' => 'Client retail'],
            ['code' => 'CL-003', 'name' => 'UD Sejahtera', 'keterangan' => 'Client grosir'],
        ];
        foreach ($clients as $client) {
            Client::firstOrCreate(['code' => $client['code']], $client);
        }

        // 5. Mesins
        $mesins = [
            ['code' => 'MSN-001', 'name' => 'Mesin Produksi A', 'keterangan' => 'Mesin utama'],
            ['code' => 'MSN-002', 'name' => 'Mesin Produksi B', 'keterangan' => 'Mesin cadangan'],
            ['code' => 'MSN-003', 'name' => 'Mesin Finishing', 'keterangan' => 'Mesin finishing'],
        ];
        foreach ($mesins as $mesin) {
            Mesin::firstOrCreate(['code' => $mesin['code']], $mesin);
        }

        // 6. Sample Transaksi Pembelian
        if (TransaksiPembelian::count() == 0) {
            $factory = Factory::first();
            $paket1 = Paket::where('code', 'PKT-001')->first();
            $paket2 = Paket::where('code', 'PKT-002')->first();

            if ($factory && $paket1 && $paket2) {
                DB::transaction(function () use ($factory, $paket1, $paket2) {
                    $transaksi = TransaksiPembelian::create([
                        'factory_id' => $factory->id,
                        'tanggal_transaksi' => now()->subDays(5),
                    ]);

                    // Detail 1 - Lunas
                    $transaksi->details()->create([
                        'paket_id' => $paket1->id,
                        'qty' => 10,
                        'harga_per_unit' => 50000,
                        'total_bayar' => 500000,
                    ]);

                    // Detail 2 - Belum Lunas
                    $transaksi->details()->create([
                        'paket_id' => $paket2->id,
                        'qty' => 5,
                        'harga_per_unit' => 100000,
                        'total_bayar' => 300000,
                    ]);
                });
            }
        }

        // 7. Sample Transaksi Mesin
        if (TransaksiMesin::count() == 0) {
            $client = Client::first();
            $mesin = Mesin::first();

            if ($client && $mesin) {
                TransaksiMesin::create([
                    'client_id' => $client->id,
                    'mesin_id' => $mesin->id,
                    'tanggal_transaksi' => now()->subDays(3),
                    'nama_produk' => 'Produk Sample A',
                    'harga_pabrik' => 500000,
                    'harga_jual' => 650000,
                    'total_harga_pabrik' => 500000,
                    'total_harga_jual' => 650000,
                    'status_lunas' => true,
                ]);

                TransaksiMesin::create([
                    'client_id' => $client->id,
                    'mesin_id' => $mesin->id,
                    'tanggal_transaksi' => now()->subDays(1),
                    'nama_produk' => 'Produk Sample B',
                    'harga_pabrik' => 750000,
                    'harga_jual' => 900000,
                    'total_harga_pabrik' => 750000,
                    'total_harga_jual' => 900000,
                    'status_lunas' => false,
                ]);
            }
        }
    }
}
