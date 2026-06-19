<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category',
        'shop_name',
        'price',
        'stock',
        'description',
        'image_url',
        'is_active',
    ];

    public static function categories(): array
    {
        return [
            'biji_kopi' => 'Biji Kopi',
            'kopi_bubuk' => 'Kopi Bubuk',
            'cold_brew' => 'Cold Brew',
            'lainnya' => 'Lainnya',
        ];
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::categories()[$this->category] ?? $this->category;
    }

    public static function categoryStyles(): array
    {
        return [
            'biji_kopi' => [
                'badge' => 'bg-[#fdf3e7] text-[#936232] border-[#e8d5c0]',
                'icon_bg' => 'bg-[#dfb287]/20 text-[#be8146]',
            ],
            'kopi_bubuk' => [
                'badge' => 'bg-[#eaf7f2] text-[#2d8a62] border-[#c5e8d8]',
                'icon_bg' => 'bg-[#70c9a5]/20 text-[#30976f]',
            ],
            'cold_brew' => [
                'badge' => 'bg-[#eef4fc] text-[#3d7ecb] border-[#d0e3f7]',
                'icon_bg' => 'bg-[#7cb0ec]/20 text-[#3d7ecb]',
            ],
            'lainnya' => [
                'badge' => 'bg-gray-100 text-gray-600 border-gray-200',
                'icon_bg' => 'bg-gray-100 text-gray-500',
            ],
        ];
    }

    public function getCategoryStyleAttribute(): array
    {
        return self::categoryStyles()[$this->category] ?? self::categoryStyles()['lainnya'];
    }

    /** Gambar andal per kategori (Unsplash + parameter crop) */
    public static function imagePool(): array
    {
        return [
            'biji_kopi' => [
                'https://images.unsplash.com/photo-1507133750040-4a8f57021571?w=600&h=440&fit=crop&q=80',
                'https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=600&h=440&fit=crop&q=80',
                'https://images.unsplash.com/photo-1511920170033-f8396924c348?w=600&h=440&fit=crop&q=80',
                'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=600&h=440&fit=crop&q=80',
            ],
            'kopi_bubuk' => [
                'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600&h=440&fit=crop&q=80',
                'https://images.unsplash.com/photo-1495474472287-4d489827aafd?w=600&h=440&fit=crop&q=80',
                'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=600&h=440&fit=crop&q=80',
                'https://images.unsplash.com/photo-1461023058943-07fcbe16d930?w=600&h=440&fit=crop&q=80',
            ],
            'cold_brew' => [
                'https://images.unsplash.com/photo-1517487881594-278f144ad4db?w=600&h=440&fit=crop&q=80',
                'https://images.unsplash.com/photo-1497935586351-b67a49e012bf?w=600&h=440&fit=crop&q=80',
                'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600&h=440&fit=crop&q=80',
            ],
            'lainnya' => [
                'https://images.unsplash.com/photo-1498804103079-a5661f93402b?w=600&h=440&fit=crop&q=80',
                'https://images.unsplash.com/photo-1507133750040-4a8f57021571?w=600&h=440&fit=crop&q=80',
            ],
        ];
    }

    public function resolveImageUrl(): string
    {
        if ($this->image_url) {
            if (str_starts_with($this->image_url, 'http://') || str_starts_with($this->image_url, 'https://')) {
                return $this->image_url;
            }

            return asset('storage/' . ltrim($this->image_url, '/'));
        }

        $pool = self::imagePool()[$this->category] ?? self::imagePool()['lainnya'];
        $index = ($this->id ?? 0) % count($pool);

        return $pool[$index];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
