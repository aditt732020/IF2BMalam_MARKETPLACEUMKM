<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketplaceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Kopi Arabika Gayo Aceh Full Wash',
                'category' => 'biji_kopi',
                'shop_name' => 'Gayo Coffee Highland',
                'price' => 85000,
                'stock' => 25,
                'description' => 'Kopi Arabika Gayo pilihan dengan proses Full Wash. Memiliki notes fruity, mild body, dan keasaman yang seimbang. Cocok untuk konsumsi harian Anda.',
                'image_url' => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=600&h=440&fit=crop&q=80',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kopi Bubuk Robusta Dampit Malang',
                'category' => 'kopi_bubuk',
                'shop_name' => 'Toko Kopi Malang City',
                'price' => 45000,
                'stock' => 40,
                'description' => 'Robusta Dampit terkenal dengan body yang tebal dan aroma cokelat yang kuat. Sangat cocok bagi pencinta kopi pahit maupun campuran kopi susu.',
                'image_url' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=600&q=80',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cold Brew Signature Mandheling 250ml',
                'category' => 'cold_brew',
                'shop_name' => 'Brew Nusantara',
                'price' => 35000,
                'stock' => 15,
                'description' => 'Kopi hitam cold brew siap minum, diseduh selama 16 jam menggunakan biji Arabika Mandheling untuk menghasilkan rasa yang smooth dan tidak terlalu asam.',
                'image_url' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=600&h=440&fit=crop&q=80',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}