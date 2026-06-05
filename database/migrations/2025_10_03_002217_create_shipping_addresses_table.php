<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('shipping_addresses', function (Illuminate\Database\Schema\Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->text('address');
        $table->string('city', 100);
        $table->string('state', 100);
        $table->string('zip_code', 20);
        $table->string('country', 100);
        $table->timestamps();
    });
}

};
