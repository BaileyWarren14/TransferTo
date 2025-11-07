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
            $table->date('date');
            $table->string('bol_number');
            $table->string('trailer');
            $table->string('from');
            $table->string('destination');
            $table->decimal('iso_capacity', 10, 2);
            $table->decimal('inches_gallon', 10, 2);
            $table->decimal('mileage_before', 10, 2);
            $table->decimal('mileage_after', 10, 2);
            $table->decimal('total_miles', 10, 2);
            $table->decimal('fuel_dispensed', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuels');
    }
};
