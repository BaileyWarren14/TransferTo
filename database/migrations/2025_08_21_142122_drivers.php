<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Libreria normalize para diferentes navegadores
     * librerias de hojas de estilos webkit
     * 
     */

    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('name');
            $table->string('lastname');
            $table->string('phone_number')->nullable();
            $table->string('email')->unique();
            $table->string('password');

            $table->string('social_security_number', 9)->unique();
            $table->string('license_number')->unique();
            $table->enum('status', ['available', 'on_trip', 'on_leave', 'unavailable'])->default('available');
            
            $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
