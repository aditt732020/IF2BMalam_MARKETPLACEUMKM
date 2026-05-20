<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'region',
        'password',
        'role',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

}