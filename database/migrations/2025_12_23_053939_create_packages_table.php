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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Standard, Full Board
            $table->enum('duration', ['2D1N', '3D2N', '4D3N']);
            $table->decimal('price_standard', 10, 2)->nullable(); // For Standard package
            $table->decimal('price_fullboard_adult', 10, 2)->nullable(); // For Full Board package
            $table->decimal('price_fullboard_child', 10, 2)->nullable(); // For Full Board package
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
