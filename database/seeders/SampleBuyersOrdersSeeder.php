<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SampleBuyersOrdersSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        if ($products->isEmpty()) {
            $this->command?->warn('Tidak ada produk. Jalankan dulu: php artisan db:seed --class=MarketplaceSeeder');

            return;
        }

        $cities = [
            ['Jakarta Pusat', 'DKI Jakarta'],
            ['Bandung', 'Jawa Barat'],
            ['Surabaya', 'Jawa Timur'],
            ['Yogyakarta', 'DI Yogyakarta'],
            ['Medan', 'Sumatera Utara'],
            ['Makassar', 'Sulawesi Selatan'],
            ['Denpasar', 'Bali'],
            ['Semarang', 'Jawa Tengah'],
            ['Palembang', 'Sumatera Selatan'],
            ['Malang', 'Jawa Timur'],
        ];

        $firstNames = ['Andi', 'Budi', 'Citra', 'Dewi', 'Eko', 'Farah', 'Gilang', 'Hana', 'Indra', 'Joko', 'Kartika', 'Lia', 'Made', 'Nina', 'Omar', 'Putri', 'Rizky', 'Sari', 'Tono', 'Umi', 'Vina', 'Wawan', 'Yuni', 'Zaki', 'Ayu', 'Bayu'];
        $lastNames = ['Santoso', 'Wijaya', 'Pratama', 'Siregar', 'Hartono', 'Lestari', 'Nugroho', 'Saputra', 'Kusuma', 'Ramadhan', 'Permata', 'Utami', 'Setiawan', 'Mahardika', 'Anggraini'];

        $statuses = ['pending', 'paid', 'shipped', 'completed', 'cancelled'];

        $buyers = [];

        for ($i = 1; $i <= 28; $i++) {
            $first = $firstNames[($i - 1) % count($firstNames)];
            $last = $lastNames[($i - 1) % count($lastNames)];
            $city = $cities[($i - 1) % count($cities)];
            $email = sprintf('pembeli%d@kopinusantara.test', $i);

            $buyer = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $first . ' ' . $last,
                    'password' => Hash::make('password'),
                    'role' => 'buyer',
                    'phone' => '0812' . str_pad((string) (1000000 + $i), 7, '0', STR_PAD_LEFT),
                    'address' => 'Jl. Kopi Nusantara No. ' . $i . ', ' . $city[0] . ', ' . $city[1],
                ]
            );

            $buyers[] = $buyer;
        }

        // Pastikan pembeli contoh utama tetap ada
        $buyers[] = User::firstOrCreate(
            ['email' => 'pembeli@kopinusantara.test'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'buyer',
                'phone' => '0812-3456-7890',
                'address' => 'Jl. Sudirman No. 45, Kel. Menteng, Jakarta Pusat, DKI Jakarta 10310',
            ]
        );

        $buyers = collect($buyers)->unique('id')->values();

        for ($i = 1; $i <= 32; $i++) {
            $product = $products->random();
            $buyer = $buyers->random();
            $qty = rand(1, 3);
            $status = $statuses[($i - 1) % count($statuses)];

            $exists = Order::where('buyer_id', $buyer->id)
                ->where('product_id', $product->id)
                ->where('quantity', $qty)
                ->where('total_price', $product->price * $qty)
                ->exists();

            if ($exists) {
                continue;
            }

            Order::create([
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
                'quantity' => $qty,
                'total_price' => $product->price * $qty,
                'status' => $status,
                'created_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23)),
                'updated_at' => now(),
            ]);
        }

        $this->command?->info('Berhasil: ' . User::where('role', 'buyer')->count() . ' pembeli, ' . Order::count() . ' pesanan.');
    }
}
