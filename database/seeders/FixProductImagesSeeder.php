<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class FixProductImagesSeeder extends Seeder
{
    public function run(): void
    {
        Product::query()->each(function (Product $product) {
            $product->update([
                'image_url' => $product->resolveImageUrl(),
            ]);
        });

        $this->command?->info('Gambar ' . Product::count() . ' produk diperbarui.');
    }
}
