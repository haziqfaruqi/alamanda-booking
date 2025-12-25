<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('payment_method', ['bank_transfer', 'online_banking', 'card'])->nullable()->after('payment_status');
            $table->string('payment_reference')->nullable()->after('payment_method');
            $table->string('receipt_path')->nullable()->after('payment_reference');
        });

        // Update payment_status enum to include 'processing'
        DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_status ENUM('unpaid', 'processing', 'paid', 'refunded') DEFAULT 'unpaid'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_reference', 'receipt_path']);
        });

        DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_status ENUM('unpaid', 'paid', 'refunded') DEFAULT 'unpaid'");
    }
};
