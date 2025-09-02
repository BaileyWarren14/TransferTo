<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('trucks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('license_plate')->unique();
            $table->string('brand');
            $table->string('model');
            $table->integer('year');
            $table->decimal('capacity_tons', 8, 2);
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->decimal('current_motor_hours', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trucks');
    }
};
