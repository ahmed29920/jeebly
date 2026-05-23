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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');

            $table->enum('type', ['product', 'category', 'cart', 'shipping'])->index();

            $table->json('condition')->nullable();

            $table->enum('discount_type', ['fixed', 'percent', 'free_shipping', 'bogo'])->default('fixed');
            $table->decimal('discount_value', 10, 2)->default(0);

            $table->boolean('is_active')->default(true);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
