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
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
            $table->dropColumn('price');
            $table->dropColumn('stock');
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage')->after('discount');
            $table->integer('max_order_quantity')->default(1)->nullable()->after('discount_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('price',8,2)->default(0);
            $table->integer('stock')->default(0);
            $table->dropColumn('discount_type');
            $table->dropColumn('max_order_quantity');
        });
    }
};
