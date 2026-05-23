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
        // First, add the new polymorphic columns
        Schema::table('product_images', function (Blueprint $table) {
            $table->unsignedBigInteger('imageable_id')->nullable()->after('id');
            $table->string('imageable_type')->nullable()->after('imageable_id');
        });

        // Migrate existing data: set imageable_id = product_id and imageable_type = 'App\Models\Product'
        DB::table('product_images')->update([
            'imageable_id' => DB::raw('product_id'),
            'imageable_type' => 'App\Models\Product'
        ]);

        // Drop foreign key constraint first
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        // Drop product_id column
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropColumn('product_id');
        });

        // Make polymorphic columns non-nullable
        Schema::table('product_images', function (Blueprint $table) {
            $table->unsignedBigInteger('imageable_id')->nullable(false)->change();
            $table->string('imageable_type')->nullable(false)->change();
        });

        // Add index for better performance
        Schema::table('product_images', function (Blueprint $table) {
            $table->index(['imageable_id', 'imageable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            // Drop index
            $table->dropIndex(['imageable_id', 'imageable_type']);
        });

        // Add back product_id column (nullable first)
        Schema::table('product_images', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->after('id');
        });

        // Migrate data back: only for Product images
        DB::table('product_images')
            ->where('imageable_type', 'App\Models\Product')
            ->update([
                'product_id' => DB::raw('imageable_id')
            ]);

        // Make product_id non-nullable and add foreign key
        Schema::table('product_images', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        // Drop polymorphic columns
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropColumn(['imageable_id', 'imageable_type']);
        });
    }
};
