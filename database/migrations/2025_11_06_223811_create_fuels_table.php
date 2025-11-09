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
        Schema::create('fuel_log_form', function (Blueprint $table) {
           $table->id();
            $table->date('date'); // Fecha del registro
            $table->string('bol_number')->nullable(); // Número de BOL
            $table->string('trailer')->nullable(); // Número de remolque
            $table->string('from')->nullable(); // Origen
            $table->string('destination')->nullable(); // Destino
            $table->decimal('iso_capacity', 10, 2)->nullable(); // Capacidad ISO
            $table->decimal('inches_gallon', 10, 2)->nullable(); // Pulgadas a galones
            $table->integer('mileage_before')->nullable(); // Kilometraje antes
            $table->integer('mileage_after')->nullable(); // Kilometraje después
            $table->integer('total_miles')->nullable(); // Total de millas
            $table->decimal('fuel_dispensed', 10, 2)->nullable(); // Combustible dispensado
            $table->decimal('efficiency', 10, 2)->nullable(); // Eficiencia
            $table->string('bol_path')->nullable(); // Ruta del archivo BOL (PDF o imagen)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuels');
    }
};
