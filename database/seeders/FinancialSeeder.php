<?php

namespace Database\Seeders;

use App\Models\Factory;
use App\Models\Fund;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Unit;
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

        // 2. Factories
        $factories = [
            ['name' => 'Pabrik A', 'code' => 'P-A'],
            ['name' => 'Pabrik B', 'code' => 'P-B'],
            ['name' => 'Pabrik C', 'code' => 'P-C'],
        ];

        foreach ($factories as $factory) {
            Factory::firstOrCreate(
                ['code' => $factory['code']],
                ['name' => $factory['name']]
            );
        }

        // 3. Products
        $products = [
            ['name' => 'Kertas Bekas', 'code' => 'KB-01', 'unit' => 'Kg'],
            ['name' => 'Plastik PET', 'code' => 'PL-01', 'unit' => 'Kg'],
            ['name' => 'Besi Tua', 'code' => 'BS-01', 'unit' => 'Kg'],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['code' => $product['code']],
                ['name' => $product['name'], 'unit' => $product['unit']]
            );
        }

        // 4. Funds (Kas)
        if (Fund::count() == 0) {
            Fund::create([
                'type' => 'IN',
                'amount' => 100000000,
                'date' => now()->subDays(10),
                'description' => 'Modal Awal',
            ]);

            Fund::create([
                'type' => 'OUT',
                'amount' => 5000000,
                'date' => now()->subDays(5),
                'description' => 'Biaya Operasional',
            ]);
        }

        // 5. Purchases (Nota Pabrik)
        if (Purchase::count() == 0) {
            $factoryA = Factory::where('code', 'P-A')->first();
            $productKertas = Product::where('code', 'KB-01')->first();
            $productPlastik = Product::where('code', 'PL-01')->first();

            if ($factoryA && $productKertas && $productPlastik) {
                DB::transaction(function () use ($factoryA, $productKertas, $productPlastik) {
                    $purchase = Purchase::create([
                        'factory_id' => $factoryA->id,
                        'date' => now()->subDays(3),
                        'grand_total' => 1500000,
                        'total_paid' => 1000000,
                        'total_debt' => 500000,
                        'is_paid' => false,
                    ]);

                    $purchase->items()->create([
                        'product_id' => $productKertas->id,
                        'weight' => 500,
                        'rejected_weight' => 10,
                        'price' => 1000000, // 2000 per kg approx
                        'debt_amount' => 0,
                        'is_printable' => true,
                    ]);

                    $purchase->items()->create([
                        'product_id' => $productPlastik->id,
                        'weight' => 200,
                        'rejected_weight' => 5,
                        'price' => 500000, // 2500 per kg approx
                        'debt_amount' => 500000,
                        'is_printable' => true,
                    ]);
                });
            }
        }

        // 6. Sales (Nota Pembeli)
        if (Sale::count() == 0) {
            $purchaseItem = \App\Models\PurchaseItem::first();

            if ($purchaseItem) {
                DB::transaction(function () use ($purchaseItem) {
                    $sale = Sale::create([
                        'buyer_name' => 'Budi Pengepul',
                        'date' => now(),
                        'grand_total' => 1200000,
                    ]);

                    $sale->items()->create([
                        'purchase_item_id' => $purchaseItem->id,
                        'selling_price' => 1200000,
                    ]);
                });
            }
        }
    }
}
