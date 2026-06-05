<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('users', function (Illuminate\Database\Schema\Blueprint $table) {
    $table->id();                               // PK estándar
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();  // 👈 agrega esta
    $table->string('password');
    $table->string('phone', 20)->nullable();
    $table->enum('role', ['admin','customer'])->default('customer');
    $table->text('address')->nullable();
    $table->rememberToken();
    $table->timestamps();
});

}


};
