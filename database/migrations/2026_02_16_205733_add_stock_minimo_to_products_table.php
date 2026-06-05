<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'stock_minimo')) {
            Schema::table('products', function (Blueprint $table) {
                $table->integer('stock_minimo')->default(10);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'stock_minimo')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('stock_minimo');
            });
        }
    }
};