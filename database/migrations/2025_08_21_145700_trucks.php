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
            $table->decimal('current_mileage', 10, 2)->default(0); // millaje actual
            $table->decimal('fuel_capacity', 8, 2)->nullable(); // capacidad de combustible
            $table->string('color')->nullable(); // color
            $table->string('cab_type')->nullable(); // tipo de cabina
            $table->string('transmission_type')->nullable(); // tipo de transmisión

            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->decimal('current_motor_hours', 10, 2)->default(0);

            // Foreign key to drivers

            $table->unsignedBigInteger('driver_id')->nullable();
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('set null');

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
