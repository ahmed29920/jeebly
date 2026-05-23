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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('delivery_id')->after('branch_id')->nullable()->constrained('deliveries')->nullOnDelete();
            $table->timestamp('delivery_assigned_at')->after('delivery_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['delivery_id']);
            $table->dropColumn('delivery_id');
            $table->dropColumn('delivery_assigned_at');
        });
    }
};
