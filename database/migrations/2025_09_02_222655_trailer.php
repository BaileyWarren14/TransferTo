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
        //
        Schema::create('trailers', function (Blueprint $table) {
        $table->bigIncrements('id');

        $table->integer('axles')->nullable(); // Número de ejes
        $table->enum('trailer_type', ['cistern', 'dry_box', 'flatbed', 'pneumatic', 'other'])->default('other'); // Tipo de caja
        $table->string('license_plate')->unique(); // Placas

        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('trailers');
    }
};
