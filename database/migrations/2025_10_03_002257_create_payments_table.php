<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->cascadeOnDelete();

            $table->enum('payment_method', ['paypal', 'stripe', 'bank_transfer']);
            $table->decimal('amount', 10, 2);
            $table->string('transaction_id')->nullable();
            $table->string('transaction_json', 255)->nullable(); // conserva tu tamaño original
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
