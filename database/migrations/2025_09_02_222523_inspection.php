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
        Schema::create('inspections', function (Blueprint $table) {
            // Primary key
            $table->bigIncrements('id');

            // Foreign keys
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('truck_id')->nullable();
            $table->unsignedBigInteger('trailer_id')->nullable();

            // Type of inspection
            $table->boolean('pre_trip')->default(false);
            $table->boolean('post_trip')->default(false);

            // Truck info
            $table->string('truck_number')->nullable();
            $table->decimal('odometer', 10, 2)->nullable();
            $table->enum('unit', ['km', 'miles'])->nullable();

            // Condition
            $table->enum('condition', ['no_defect', 'defects'])->nullable();

            // Checklist - first column
            $table->boolean('air_compressor')->default(false);
            $table->boolean('air_lines')->default(false);
            $table->boolean('axles')->default(false);
            $table->boolean('battery')->default(false);
            $table->boolean('belts')->default(false);
            $table->boolean('body_frame')->default(false);
            $table->boolean('brakes_adjustment')->default(false);
            $table->boolean('brakes_service')->default(false);
            $table->boolean('brakes_parking')->default(false);
            $table->boolean('charging_system')->default(false);
            $table->boolean('clutch')->default(false);
            $table->boolean('cooling_system')->default(false);
            $table->boolean('coupling_devices')->default(false);
            $table->boolean('documents')->default(false);
            $table->boolean('doors')->default(false);

            // Checklist - second column
            $table->boolean('drive_lines')->default(false);
            $table->boolean('emergency_equipment')->default(false);
            $table->boolean('emergency_windows')->default(false);
            $table->boolean('engine')->default(false);
            $table->boolean('exhaust_system')->default(false);
            $table->boolean('fire_extinguishers')->default(false);
            $table->boolean('first_aid')->default(false);
            $table->boolean('fluid_leaks')->default(false);
            $table->boolean('frame')->default(false);
            $table->boolean('fuel_system')->default(false);
            $table->boolean('heater')->default(false);
            $table->boolean('horns')->default(false);
            $table->boolean('inspection_decals')->default(false);
            $table->boolean('interior_ligths')->default(false);
            $table->boolean('lights_reflectors')->default(false);

            // Checklist - third column
            $table->boolean('load_security_device')->default(false);
            $table->boolean('lubrication_system')->default(false);
            $table->boolean('mirrows')->default(false);
            $table->boolean('mud_flaps')->default(false);
            $table->boolean('oil_pressure')->default(false);
            $table->boolean('rear_end')->default(false);
            $table->boolean('recording_devices')->default(false);
            $table->boolean('seats')->default(false);
            $table->boolean('suspension')->default(false);
            $table->boolean('steering_mechanism')->default(false);
            $table->boolean('transmission')->default(false);
            $table->boolean('wheels_tires')->default(false);
            $table->boolean('windows')->default(false);
            $table->boolean('wipers')->default(false);
            $table->boolean('other')->default(false);

            // Trailer info
            $table->string('trailer1')->nullable();
            $table->string('trailer2')->nullable();

            // Remarks
            $table->text('remarks')->nullable();

            // Driver signature and date/time
            $table->string('signature')->nullable();
            $table->date('inspection_date')->nullable();
            $table->time('inspection_time')->nullable();

            // Timestamps
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('set null');
            $table->foreign('truck_id')->references('id')->on('trucks')->onDelete('set null');
            
        });

        /*
        Schema::Schema::table('inspections', function (Blueprint $table) {
            $table->unsignedBigInteger('trailer_id')->nullable()->after('truck_id');
            $table->foreign('trailer_id')->references('id')->on('trailers')->onDelete('set null');
        });;
     Schema::dropIfExists('inspections');*/
    }
      
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('inspections');
    }
};
