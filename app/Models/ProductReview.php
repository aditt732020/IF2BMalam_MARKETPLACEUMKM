<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    // Mengizinkan kolom-kolom ini agar dapat diisi secara massal (mass assignment)
    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
    ];

    /**
     * Relasi balik ke model Product (Satu ulasan dimiliki oleh satu produk)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relasi balik ke model User (Satu ulasan ditulis oleh satu user)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
