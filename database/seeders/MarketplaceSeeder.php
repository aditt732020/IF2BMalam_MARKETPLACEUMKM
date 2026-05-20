<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MarketplaceSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = [
            ['name' => 'Rumah Kopi Aceh', 'email' => 'aceh@kopinusantara.test', 'region' => 'Aceh Tengah'],
            ['name' => 'Kopi Siger', 'email' => 'siger@kopinusantara.test', 'region' => 'Lampung Barat'],
            ['name' => 'Bali Coffee Co.', 'email' => 'bali@kopinusantara.test', 'region' => 'Kintamani, Bali'],
            ['name' => 'Kopi Ende NTT', 'email' => 'ende@kopinusantara.test', 'region' => 'Flores, NTT'],
        ];

        foreach ($sellers as $seller) {
            User::firstOrCreate(
                ['email' => $seller['email']],
                [
                    'name' => $seller['name'],
                    'password' => Hash::make('password'),
                    'role' => 'seller',
                    'region' => $seller['region'],
                ]
            );
        }

        $products = [
            [
                'name' => 'Arabika Gayo Premium',
                'shop_name' => 'Rumah Kopi Aceh',
                'category' => 'biji_kopi',
                'price' => 85000,
                'stock' => 48,
                'description' => 'Arabika Gayo dari ketinggian 1.400 mdpl. Proses natural sun-dry menghasilkan cita rasa fruity dengan body sedang dan after taste dark chocolate yang khas. Cocok untuk metode pour over dan V60.',
                'image_url' => 'https://images.unsplash.com/photo-1507133750040-4a8f57021571?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Robusta Lampung',
                'shop_name' => 'Kopi Siger',
                'category' => 'biji_kopi',
                'price' => 55000,
                'stock' => 35,
                'description' => 'Kopi Robusta asli Lampung dengan body tebal, rasa cokelat yang pekat, dan keasaman yang sangat rendah. Di-roast secara merata untuk menjaga kekuatan rasa kopinya.',
                'image_url' => 'https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Kintamani Natural',
                'shop_name' => 'Bali Coffee Co.',
                'category' => 'kopi_bubuk',
                'price' => 92000,
                'stock' => 22,
                'description' => 'Kopi Kintamani Bali yang diproses secara natural, menghasilkan karakter rasa sitrus jeruk segar yang dikombinasikan manis karamel alami.',
                'image_url' => 'https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Flores Bajawa',
                'shop_name' => 'Kopi Ende NTT',
                'category' => 'cold_brew',
                'price' => 78000,
                'stock' => 30,
                'description' => 'Kopi organik terpopuler dari Bajawa Flores, memiliki sensasi rasa cokelat kacang dengan keharuman bunga (floral aroma) yang legit.',
                'image_url' => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=600&q=80',
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['name' => $product['name']],
                array_merge($product, ['is_active' => true])
            );
        }

        User::firstOrCreate(
            ['email' => 'admin@kopinusantara.test'],
            [
                'name' => 'Admin KopiNusantara',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'pembeli@kopinusantara.test'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'buyer',
                'phone' => '0812-3456-7890',
                'address' => 'Jl. Sudirman No. 45, Kel. Menteng, Jakarta Pusat, DKI Jakarta 10310',
            ]
        );
    }
}
