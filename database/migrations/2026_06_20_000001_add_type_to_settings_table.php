<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('settings', 'type')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->string('type')->default('text')->after('value');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('settings', 'type')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
};
