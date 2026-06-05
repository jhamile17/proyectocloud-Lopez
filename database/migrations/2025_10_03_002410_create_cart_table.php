<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete();

            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('price', 10, 2);
            $table->decimal('sub_total', 10, 2);

            $table->timestamps();

            // Evita que el mismo producto se repita para el mismo user
            $table->unique(['user_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
