<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class SampleProductsSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Toraja Sapan', 'shop_name' => 'Rumah Kopi Aceh', 'category' => 'biji_kopi', 'price' => 95000, 'stock' => 40, 'image_url' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&w=600&q=80', 'description' => 'Kopi Toraja dengan karakter earthy, rempah, dan body penuh. Cocok untuk espresso dan tubruk.'],
            ['name' => 'Mandheling Grade 1', 'shop_name' => 'Rumah Kopi Aceh', 'category' => 'biji_kopi', 'price' => 88000, 'stock' => 25, 'image_url' => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=600&q=80', 'description' => 'Arabika Mandheling Sumatera dengan cita rasa cokelat dan kacang yang lembut.'],
            ['name' => 'Aceh Gayo Wine Process', 'shop_name' => 'Rumah Kopi Aceh', 'category' => 'biji_kopi', 'price' => 120000, 'stock' => 18, 'image_url' => 'https://images.unsplash.com/photo-1507133750040-4a8f57021571?auto=format&fit=crop&w=600&q=80', 'description' => 'Proses wine menghasilkan rasa buah beri dan wine yang kompleks.'],
            ['name' => 'Robusta Temanggung', 'shop_name' => 'Kopi Siger', 'category' => 'biji_kopi', 'price' => 48000, 'stock' => 60, 'image_url' => 'https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&fit=crop&w=600&q=80', 'description' => 'Robusta Jawa dengan kafein tinggi, ideal untuk kopi susu dan espresso blend.'],
            ['name' => 'Lampung Honey Process', 'shop_name' => 'Kopi Siger', 'category' => 'biji_kopi', 'price' => 72000, 'stock' => 33, 'image_url' => 'https://images.unsplash.com/photo-1495474472287-4d489827aafd?auto=format&fit=crop&w=600&q=80', 'description' => 'Honey process memberikan sweetness alami dan body creamy.'],
            ['name' => 'Espresso Blend Siger', 'shop_name' => 'Kopi Siger', 'category' => 'kopi_bubuk', 'price' => 65000, 'stock' => 45, 'image_url' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=600&q=80', 'description' => 'Campuran Arabika-Robusta siap seduh, dipesan untuk mesin espresso.'],
            ['name' => 'Kintamani Washed', 'shop_name' => 'Bali Coffee Co.', 'category' => 'biji_kopi', 'price' => 89000, 'stock' => 28, 'image_url' => 'https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&w=600&q=80', 'description' => 'Proses washed menghasilkan keasaman citrus yang bersih dan floral.'],
            ['name' => 'Bali Blue Moon', 'shop_name' => 'Bali Coffee Co.', 'category' => 'biji_kopi', 'price' => 105000, 'stock' => 15, 'image_url' => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d930?auto=format&fit=crop&w=600&q=80', 'description' => 'Arabika Bali dengan aroma floral dan rasa dark chocolate.'],
            ['name' => 'Kopi Bubuk Kintamani', 'shop_name' => 'Bali Coffee Co.', 'category' => 'kopi_bubuk', 'price' => 58000, 'stock' => 50, 'image_url' => 'https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&fit=crop&w=600&q=80', 'description' => 'Kopi bubuk medium roast, praktis untuk french press dan moka pot.'],
            ['name' => 'Flores Bajawa Organic', 'shop_name' => 'Kopi Ende NTT', 'category' => 'biji_kopi', 'price' => 82000, 'stock' => 20, 'image_url' => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=600&q=80', 'description' => 'Kopi organik sertifikasi dari petani Bajawa, rasa cokelat dan floral.'],
            ['name' => 'Ngada Single Origin', 'shop_name' => 'Kopi Ende NTT', 'category' => 'biji_kopi', 'price' => 76000, 'stock' => 24, 'image_url' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80', 'description' => 'Single origin Flores dengan keasaman medium dan aftertaste panjang.'],
            ['name' => 'Cold Brew Flores Ready', 'shop_name' => 'Kopi Ende NTT', 'category' => 'cold_brew', 'price' => 45000, 'stock' => 55, 'image_url' => 'https://images.unsplash.com/photo-1517487881594-278f144ad4db?auto=format&fit=crop&w=600&q=80', 'description' => 'Botol cold brew siap minum 250ml, ekstraksi 18 jam.'],
            ['name' => 'Cold Brew Gayo', 'shop_name' => 'Rumah Kopi Aceh', 'category' => 'cold_brew', 'price' => 42000, 'stock' => 38, 'image_url' => 'https://images.unsplash.com/photo-1517487881594-278f144ad4db?auto=format&fit=crop&w=600&q=80', 'description' => 'Cold brew Arabika Gayo, smooth dan rendah asam.'],
            ['name' => 'Nitro Cold Brew Bali', 'shop_name' => 'Bali Coffee Co.', 'category' => 'cold_brew', 'price' => 55000, 'stock' => 30, 'image_url' => 'https://images.unsplash.com/photo-1495474472287-4d489827aafd?auto=format&fit=crop&w=600&q=80', 'description' => 'Cold brew dengan nitrogen, tekstur creamy seperti draft beer.'],
            ['name' => 'V60 Drip Bag Siger', 'shop_name' => 'Kopi Siger', 'category' => 'kopi_bubuk', 'price' => 35000, 'stock' => 80, 'image_url' => 'https://images.unsplash.com/photo-1498804103079-a5661f93402b?auto=format&fit=crop&w=600&q=80', 'description' => 'Drip bag isi 5 sachet, praktis untuk traveling.'],
            ['name' => 'Kopi Instan Premium Ende', 'shop_name' => 'Kopi Ende NTT', 'category' => 'kopi_bubuk', 'price' => 38000, 'stock' => 70, 'image_url' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=600&q=80', 'description' => 'Kopi bubuk instan tanpa gula, aroma khas Flores.'],
            ['name' => 'Java Preanger', 'shop_name' => 'Kopi Siger', 'category' => 'biji_kopi', 'price' => 68000, 'stock' => 32, 'image_url' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&w=600&q=80', 'description' => 'Arabika Jawa Barat dengan rasa herbal dan spicy notes.'],
            ['name' => 'Papua Wamena', 'shop_name' => 'Rumah Kopi Aceh', 'category' => 'biji_kopi', 'price' => 135000, 'stock' => 12, 'image_url' => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d930?auto=format&fit=crop&w=600&q=80', 'description' => 'Kopi langka dari dataran tinggi Papua, fruity dan kompleks.'],
            ['name' => 'Blend Nusantara 250g', 'shop_name' => 'Bali Coffee Co.', 'category' => 'kopi_bubuk', 'price' => 52000, 'stock' => 42, 'image_url' => 'https://images.unsplash.com/photo-1498804103079-a5661f93402b?auto=format&fit=crop&w=600&q=80', 'description' => 'Blend Aceh, Toraja, dan Bali untuk rasa seimbang sehari-hari.'],
            ['name' => 'Decaf Arabika', 'shop_name' => 'Kopi Ende NTT', 'category' => 'lainnya', 'price' => 98000, 'stock' => 16, 'image_url' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80', 'description' => 'Kopi tanpa kafein, tetap aromatik untuk malam hari.'],
            ['name' => 'Green Bean Gayo', 'shop_name' => 'Rumah Kopi Aceh', 'category' => 'lainnya', 'price' => 75000, 'stock' => 22, 'image_url' => 'https://images.unsplash.com/photo-1507133750040-4a8f57021571?auto=format&fit=crop&w=600&q=80', 'description' => 'Biji kopi mentah untuk home roaster, moisture terjaga.'],
            ['name' => 'Cold Brew Mix Pack', 'shop_name' => 'Kopi Siger', 'category' => 'cold_brew', 'price' => 125000, 'stock' => 20, 'image_url' => 'https://images.unsplash.com/photo-1517487881594-278f144ad4db?auto=format&fit=crop&w=600&q=80', 'description' => 'Paket 6 botol cold brew berbagai origin Nusantara.'],
            ['name' => 'Kopi Susu Bubuk Aceh', 'shop_name' => 'Rumah Kopi Aceh', 'category' => 'kopi_bubuk', 'price' => 42000, 'stock' => 65, 'image_url' => 'https://images.unsplash.com/photo-1495474472287-4d489827aafd?auto=format&fit=crop&w=600&q=80', 'description' => 'Kopi bubuk dengan creamer alami, tinggal seduh air panas.'],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['name' => $product['name']],
                array_merge($product, ['is_active' => true])
            );
        }

        $active = Product::where('is_active', true)->where('stock', '>', 0)->count();
        $this->command?->info("Berhasil: {$active} produk aktif siap ditampilkan di toko pembeli & admin.");
    }
}
