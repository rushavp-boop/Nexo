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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->string('produce_name'); // e.g., "Large tomato (Indian)"
            $table->string('nepali_name')->nullable(); // Nepali translation
            $table->string('unit')->default('K.G.'); // Unit of measurement
            $table->decimal('min_price', 10, 2); // Minimum price
            $table->decimal('max_price', 10, 2); // Maximum price
            $table->decimal('avg_price', 10, 2); // Average price
            $table->date('price_date')->default(now()); // Date of the price
            $table->timestamps();
            $table->index('price_date');
            $table->index('produce_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
