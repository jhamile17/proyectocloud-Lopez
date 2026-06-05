<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('orders', function (Illuminate\Database\Schema\Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('shipping_address_id')->constrained('shipping_addresses')->cascadeOnDelete();
        $table->decimal('total_price', 10, 2);
        $table->enum('status', ['pending','paid','shipped','cancelled'])->default('pending');
        $table->timestamps();
    });
}

};
