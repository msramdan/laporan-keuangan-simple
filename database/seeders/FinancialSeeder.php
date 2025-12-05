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

        // 2. Factories (Pabrik)
        if (Factory::count() == 0) {
            $factories = [
                ['name' => 'Pabrik A', 'code' => 'P-A'],
                ['name' => 'Pabrik B', 'code' => 'P-B'],
                ['name' => 'Pabrik C', 'code' => 'P-C'],
                ['name' => 'Pabrik D', 'code' => 'P-D'],
                ['name' => 'Pabrik E', 'code' => 'P-E'],
            ];
            foreach ($factories as $f) {
                Factory::create($f);
            }
        }

        // 3. Products (Barang)
        if (Product::count() == 0) {
            $products = [
                ['name' => 'Kertas Bekas', 'code' => 'KB-01', 'unit' => 'Kg'],
                ['name' => 'Plastik PET', 'code' => 'PL-01', 'unit' => 'Kg'],
                ['name' => 'Besi Tua', 'code' => 'BS-01', 'unit' => 'Kg'],
                ['name' => 'Kardus', 'code' => 'KD-01', 'unit' => 'Kg'],
                ['name' => 'Tembaga', 'code' => 'TB-01', 'unit' => 'Kg'],
            ];
            foreach ($products as $p) {
                Product::create($p);
            }
        }

        // 4. Funds (Kas)
        if (Fund::count() == 0) {
            $funds = [
                ['date' => now()->subDays(5), 'type' => 'IN', 'amount' => 10000000, 'description' => 'Modal Awal'],
                ['date' => now()->subDays(4), 'type' => 'OUT', 'amount' => 500000, 'description' => 'Beli Perlengkapan'],
                ['date' => now()->subDays(3), 'type' => 'IN', 'amount' => 2000000, 'description' => 'Setoran Tambahan'],
                ['date' => now()->subDays(2), 'type' => 'OUT', 'amount' => 100000, 'description' => 'Biaya Transport'],
                ['date' => now()->subDays(1), 'type' => 'OUT', 'amount' => 50000, 'description' => 'Biaya Makan'],
            ];
            foreach ($funds as $f) {
                Fund::create($f);
            }
        }

        // 5. Purchases (Nota Pabrik)
        if (Purchase::count() == 0) {
            $factoryA = Factory::where('code', 'P-A')->first();
            $factoryB = Factory::where('code', 'P-B')->first();
            $productKertas = Product::where('code', 'KB-01')->first();
            $productPlastik = Product::where('code', 'PL-01')->first();
            $productBesi = Product::where('code', 'BS-01')->first();

            if ($factoryA && $productKertas && $productPlastik) {
                DB::transaction(function () use ($factoryA, $factoryB, $productKertas, $productPlastik, $productBesi) {
                    // Purchase 1
                    $purchase1 = Purchase::create([
                        'factory_id' => $factoryA->id,
                        'date' => now()->subDays(3),
                        'grand_total' => 1467500,
                        'total_paid' => 980000,
                        'total_debt' => 487500,
                        'is_paid' => false,
                    ]);
                    $purchase1->items()->create([
                        'product_id' => $productKertas->id,
                        'weight' => 500, 'rejected_weight' => 10, 'unit_price' => 2000, 'price' => 980000, 'debt_amount' => 0, 'is_printable' => true,
                    ]);
                    $purchase1->items()->create([
                        'product_id' => $productPlastik->id,
                        'weight' => 200, 'rejected_weight' => 5, 'unit_price' => 2500, 'price' => 487500, 'debt_amount' => 487500, 'is_printable' => true,
                    ]);

                    // Purchase 2 (Paid)
                    $purchase2 = Purchase::create([
                        'factory_id' => $factoryB->id,
                        'date' => now()->subDays(2),
                        'grand_total' => 500000,
                        'total_paid' => 500000,
                        'total_debt' => 0,
                        'is_paid' => true,
                    ]);
                    $purchase2->items()->create([
                        'product_id' => $productBesi->id,
                        'weight' => 100, 'rejected_weight' => 0, 'unit_price' => 5000, 'price' => 500000, 'debt_amount' => 0, 'is_printable' => true,
                    ]);

                    // Purchase 3 (Unpaid)
                    $purchase3 = Purchase::create([
                        'factory_id' => $factoryA->id,
                        'date' => now()->subDays(1),
                        'grand_total' => 200000,
                        'total_paid' => 0,
                        'total_debt' => 200000,
                        'is_paid' => false,
                    ]);
                    $purchase3->items()->create([
                        'product_id' => $productKertas->id,
                        'weight' => 100, 'rejected_weight' => 0, 'unit_price' => 2000, 'price' => 200000, 'debt_amount' => 200000, 'is_printable' => true,
                    ]);

                    // Purchase 4 (Paid)
                    $purchase4 = Purchase::create([
                        'factory_id' => $factoryB->id,
                        'date' => now(),
                        'grand_total' => 1000000,
                        'total_paid' => 1000000,
                        'total_debt' => 0,
                        'is_paid' => true,
                    ]);
                    $purchase4->items()->create([
                        'product_id' => $productPlastik->id,
                        'weight' => 400, 'rejected_weight' => 0, 'unit_price' => 2500, 'price' => 1000000, 'debt_amount' => 0, 'is_printable' => true,
                    ]);

                    // Purchase 5 (Unpaid)
                    $purchase5 = Purchase::create([
                        'factory_id' => $factoryA->id,
                        'date' => now(),
                        'grand_total' => 300000,
                        'total_paid' => 0,
                        'total_debt' => 300000,
                        'is_paid' => false,
                    ]);
                    $purchase5->items()->create([
                        'product_id' => $productBesi->id,
                        'weight' => 60, 'rejected_weight' => 0, 'unit_price' => 5000, 'price' => 300000, 'debt_amount' => 300000, 'is_printable' => true,
                    ]);
                });
            }
        }

        // 6. Sales (Nota Pembeli)
        if (Sale::count() == 0) {
            $purchaseItems = \App\Models\PurchaseItem::take(5)->get();

            if ($purchaseItems->count() > 0) {
                DB::transaction(function () use ($purchaseItems) {
                    $buyers = ['Budi', 'Andi', 'Citra', 'Dewi', 'Eko'];
                    
                    foreach ($purchaseItems as $index => $item) {
                        $sellingPrice = $item->price * 1.2; // 20% margin
                        $receivable = ($index % 2 == 0) ? 0 : $sellingPrice * 0.5; // Alternating receivable

                        $sale = Sale::create([
                            'buyer_name' => $buyers[$index] ?? 'Pembeli Umum',
                            'date' => now()->subDays(5 - $index),
                            'grand_total' => $sellingPrice,
                            'total_receivable' => $receivable,
                            'total_paid' => $sellingPrice - $receivable,
                            'is_paid' => $receivable == 0,
                        ]);

                        $sale->items()->create([
                            'purchase_item_id' => $item->id,
                            'selling_price' => $sellingPrice,
                            'receivable_amount' => $receivable,
                        ]);
                    }
                });
            }
        }
    }
}
