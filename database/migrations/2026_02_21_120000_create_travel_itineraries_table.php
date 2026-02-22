<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('travel_itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('destination');
            $table->string('budget');
            $table->unsignedInteger('days');
            $table->json('itinerary_data');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('travel_itineraries');
    }
};
