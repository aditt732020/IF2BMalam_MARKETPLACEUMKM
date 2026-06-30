<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Product::query()
            ->where(function ($query) {
                $query->where('category', 'cold_brew')
                    ->orWhere('name', 'like', '%cold brew%')
                    ->orWhere('name', 'like', '%Cold Brew%');
            })
            ->delete();
    }

    public function down(): void
    {
        // Data cold brew dihapus permanen; tidak dipulihkan saat rollback.
    }
};
