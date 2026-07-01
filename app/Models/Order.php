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
        'payment_method',
        'payment_bank',
        'payment_proof_path',
        'payment_proof_uploaded_at',
        'payment_verified_at',
        'payment_verified_by',
        'payment_rejection_reason',
    ];

    protected $casts = [
        'payment_proof_uploaded_at' => 'datetime',
        'payment_verified_at' => 'datetime',
    ];


    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function paymentVerifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payment_verified_by');
    }

    public function hasPaymentProof(): bool
    {
        return !empty($this->payment_proof_path);
    }

    public function isAwaitingPaymentVerification(): bool
    {
        return $this->status === 'pending'
            && $this->hasPaymentProof()
            && !$this->payment_verified_at
            && empty($this->payment_rejection_reason);
    }

    public function paymentVerificationLabel(): string
    {
        if ($this->status !== 'pending') {
            return '';
        }

        if ($this->payment_verified_at) {
            return 'Pembayaran terverifikasi';
        }

        if ($this->payment_rejection_reason) {
            return 'Bukti ditolak — unggah ulang';
        }

        if ($this->hasPaymentProof()) {
            return 'Menunggu verifikasi admin';
        }

        return 'Belum upload bukti pembayaran';
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
