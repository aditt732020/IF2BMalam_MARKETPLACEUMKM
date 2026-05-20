<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('category')->default('biji_kopi')->after('name');
            $table->string('shop_name')->nullable()->after('category');
            $table->string('image_url')->nullable()->after('description');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->string('region')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['category', 'shop_name', 'image_url']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address', 'region']);
        });
    }
};
