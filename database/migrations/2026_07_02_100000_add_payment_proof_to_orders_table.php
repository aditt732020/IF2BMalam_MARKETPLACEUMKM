<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('qr_code');
            $table->string('payment_bank')->nullable()->after('payment_method');
            $table->string('payment_proof_path')->nullable()->after('payment_bank');
            $table->timestamp('payment_proof_uploaded_at')->nullable()->after('payment_proof_path');
            $table->timestamp('payment_verified_at')->nullable()->after('payment_proof_uploaded_at');
            $table->foreignId('payment_verified_by')->nullable()->after('payment_verified_at')->constrained('users')->nullOnDelete();
            $table->text('payment_rejection_reason')->nullable()->after('payment_verified_by');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['payment_verified_by']);
            $table->dropColumn([
                'payment_method',
                'payment_bank',
                'payment_proof_path',
                'payment_proof_uploaded_at',
                'payment_verified_at',
                'payment_verified_by',
                'payment_rejection_reason',
            ]);
        });
    }
};
