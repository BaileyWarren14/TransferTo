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
        /*Schema::create('inspections', function (Blueprint $table) {
           
            $table->id();
            $table->unsignedBigInteger('driver_id');
            $table->string('truck_number')->nullable();
            $table->string('odometer')->nullable();
            $table->string('unit')->nullable(); // km o miles
            $table->boolean('pre_trip')->default(false);
            $table->boolean('post_trip')->default(false);
            $table->json('checklist')->nullable(); // JSON
            $table->string('trailer1')->nullable();
            $table->string('trailer2')->nullable();
            $table->text('remarks')->nullable();
            $table->string('signature')->nullable();
            $table->date('inspection_date')->nullable();
            $table->time('inspection_time')->nullable();
            $table->timestamps();
        });

        /*
        Schema::Schema::table('inspections', function (Blueprint $table) {
            $table->unsignedBigInteger('trailer_id')->nullable()->after('truck_id');
            $table->foreign('trailer_id')->references('id')->on('trailers')->onDelete('set null');
        });;
     Schema::dropIfExists('inspections');*/
      Schema::table('inspections', function (Blueprint $table) {
        $table->string('conditions')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        //Schema::dropIfExists('inspections');
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn('conditions');
        });
    }
};
