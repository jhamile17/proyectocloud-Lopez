<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // 👈 PK estándar "id"

            // Mantenemos tus columnas de relación tal cual (sin FK por ahora)
            $table->unsignedBigInteger('categories_id')->nullable(); // relación con categories (la FK la añadimos luego)
            $table->unsignedBigInteger('brand_id')->nullable();     // relación con brands (la FK la añadimos luego)

            $table->string('name', 255);
            $table->string('slug', 255)->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->string('image', 255)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();

            // 🔒 NOTA: No agregamos aquí claves foráneas a categories/brands
            // para evitar errores si esas tablas no usan "id" como PK.
            // Más adelante, cuando estandaricemos categories/brands a "id",
            // añadimos las FKs con una migración separada.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
