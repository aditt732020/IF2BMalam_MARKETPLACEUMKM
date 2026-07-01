<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'buyer_id',
        'product_id',
        'quantity',
        'total_price',
        'status',
        'payment_reference',
        'qr_code',
    ];


    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public static function statuses(): array
    {
        return [
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Sudah Dibayar',
            'shipped' => 'Dalam Pengiriman',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statuses()[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'amber',
            'paid' => 'blue',
            'shipped' => 'indigo',
            'completed' => 'emerald',
            'cancelled' => 'red',
            default => 'gray',
        };
    }
}
