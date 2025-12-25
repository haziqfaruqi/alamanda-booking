<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('original_price', 10, 2)->nullable()->after('total_price');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('original_price');
            $table->string('coupon_code')->nullable()->after('discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['original_price', 'discount_amount', 'coupon_code']);
        });
    }
};
